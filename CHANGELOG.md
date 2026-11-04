# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.12.0 - 2026-11-04
### Fixed
- An amount or operand ending in a line feed is now rejected instead of silently becoming `0`. `"12.34\n"` and `"1234\n"` used to pass validation and then count as `0` in bcmath on PHP 7, or throw `ValueError` on PHP 8. Affected public entry points:
  - `new Money()`, `Money::create()`, `Money::createFromCents()`, `Money::createFromMinorUnits()` and `Money::createFromNoDelimiterAmount()` throw `MoneyException`
  - `Money::mul()` and `Money::div()` validate their operand and throw `MoneyException` (on PHP 7, `div("2\n")` used to return `0` with a division-by-zero warning)
  - `MoneyFactory::create()` throws `MoneyException`, and `MoneyFactory::createFromCents()` throws Maba's `InvalidNumberException`, the classes they already threw for an invalid amount
  - `MoneyNormalizer::mapToEntity()`, `mapFromAmount()`, `mapFromCents()` and `mapFromMinorUnits()` throw `InvalidDataException('Invalid amount specified')`

  Amounts read line by line (e.g. with `fgets`) must be trimmed before they are passed in.
- `Money::mul()` and `Money::div()` pass a float operand to bcmath in plain decimal notation, with the same 14 significant digits PHP itself prints for the float (its default `precision=14`) and independent of the locale. A float whose absolute value is below `1e-4` or at least `1e14`, which PHP writes in scientific notation (e.g. `3.5E-5`, `1.5E+14`), used to count as `0` on PHP 7 and throw `ValueError` on PHP 8; it now gives the result for that float rounded to 14 significant digits (`1.5E+14` as `150000000000000`, `123456789012345.0` as `123456789012340`). The same applies on PHP 7 under a locale with a decimal comma, where every float was written as e.g. `1,5`. Every other float gives the same result as before with the default `precision=14`. Only `mul()` and `div()` convert floats: `new Money()` still rejects a float PHP writes in scientific notation, e.g. `new Money(1.0E+15)`
- `Money::createFromNoDelimiterAmount()` with a fraction of `0` keeps every digit: `('1234', 'JPY', 0)` used to give `0.1234`
- `Money::createFromNoDelimiterAmount()` accepts a leading `+` and drops it: `"+5"` gives `0.05` (it used to fail with `Amount has invalid chars: 0.+5`), and `"+1234"` gives `12.34` (it used to keep the sign: `getAmount()` returned `+12.34`, the same value)

### Changed
- `Money::createFromNoDelimiterAmount()` validates its raw input first and reports the value it was given (`Invalid amount without delimiter: …`). A sign with no digits (`"-"`, `"+"`) is rejected; `"-"` used to give `-0.00`
- `MoneyFactory::create()` rejects an array, an object without `__toString()` or a boolean amount, and a currency that is neither a string nor an object with `__toString()`, with `MoneyException`, the class it already threw for an invalid amount or an unsupported currency. An array or an object without `__toString()` used to give a PHP warning or error, and `true` used to give `1`. An amount object with `__toString()` is accepted, as by `mul()`/`div()`, and a currency object with `__toString()` still works
- `MoneyFactory::createFromCents()` checks its input with the number pattern of Maba's `NumberValidator`, anchored at the true end of the string, so, compared with the default Maba math, which already validated its input with that pattern, the only numbers it newly rejects are those ending in a line feed: `"1050.00"` still gives `10.50`, `"12.5"` still gives `0.125`, and `"+500"` is still rejected. It also rejects a boolean (`true` used to give `0.01`) and accepts an object with `__toString()`, as `Money::createFromCents()` does (it used to throw `Given number is not scalar`). Invalid input throws Maba's `InvalidNumberException`, the class it already threw, now always with the message `Amount in cents must be a number` (it used to carry Maba's own message, e.g. `Invalid number +500`)
- `Money::mul()` and `Money::div()` reject a string operand that is not a number (a trailing line feed, a decimal comma such as `"2,5"`, a sign or a point with no digits: `'+'`, `'-'`, `'.'`), an array, and `NAN`/`INF`. An array used to give `0` from `mul()` on PHP 7 (a `TypeError` on PHP 8) and the same `\Exception('Right operand is empty/zero!')` from `div()` as `div(null)` below. On PHP 7 bcmath read all of these strings as `0`, so `mul()` gave `0` and `div()` gave a division-by-zero warning. On PHP 8 a line feed or a decimal comma threw `ValueError`, while a lone sign or point was still read as `0` (`mul()` gave `0`, `div()` threw `DivisionByZeroError`). Integers, `".5"`, `"5."` and stringable objects still work, and `null`, `false` and `''` (also from a stringable object) still count as `0`. `div(null)`, `div('')` and `div()` with a stringable object that gives `''` now throw `MoneyException('Division by zero')`, like `div(false)` and `div('0')`; the first two used to throw a plain `\Exception('Right operand is empty/zero!')` from the library's deprecated `BcMath`, and the last PHP's `DivisionByZeroError` on PHP 8 (a division-by-zero warning on PHP 7)
- `Money::createFromNoDelimiterAmount()` rejects a fraction that is not an `int`, an integer string or an integral float (e.g. `null`, `false`, `true`, `2.5`, `'2.0'`, `'+2'`, `' 2'`, `"2\n"`), is negative, or is greater than `20`, with `MoneyException`. An integer string or an integral float (`'2'`, `2.0`) still works like `2`. A fraction above `20` used to be accepted (`('1', 'EUR', 21)` gave `0.000000000000000000001`), and one beyond the integer range (`'99999999999999999999'`, `1.8446744073709552E+19`) threw `TypeError` on PHP 8. A negative fraction used to give a wrong amount with a warning on PHP 7 (`('1234', 'EUR', -1)` gave `1.234`) and a `ValueError` on PHP 8; `null` and `false` were read as `0` (`('5', 'EUR', null)` gave `0.5`, `('1234', 'EUR', false)` gave `0.1234`), `2.5`, `'2.0'`, `'+2'`, `' 2'` and `"2\n"` worked like `2`, and `true` like `1`
- `MoneyException` messages thrown by `Money` escape ASCII control characters and backslashes in the rejected amount, operand or currency (`"12.34\n"` is reported as `12.34\n`), so a message can no longer split a log line. Messages from `CurrencyIsoNumber` are unchanged

## 2.11.0 - 2026-09-30
### Added
- Add Symfony 7.4 support

## 2.10.0 - 2025-04-23
### Added
- South Sudanese pound currency money fraction

## 2.9.0 - 2024-12-16
### Added
- Add Symfony 6 support

## 2.8.0 - 2023-04-20
### Removed
- Drop support for PHP 5.6

### Changed
- Mark `Money::setAmount()` as `@internal`
- Mark `Money::setCurrency()` as `@internal`
- Mark `Money::setAmountInCents()` as `@internal`
- Mark `Money::setAmountInMinorUnits()` as `@internal`
- Update PHPUnit version
- Migrate from Travis CI to GitHub Actions

### Deprecated
- Deprecate `Money::setAmountInCents()` in favor of `Money::setAmountInMinorUnits()`
- Deprecate `Money::getAmountInCents()` in favor of `Money::getAmountInMinorUnits()`

## 2.7.0 - 2023-04-05
### Added
- Add license (#21)
- Add missing soft dependencies (#22)

### Changed
- Switch to PSR-4 autoloader (#23)

## 2.6.0 - 2021-09-13
### Added
- Mauritanian Ouguiya currency iso number
- Mauritanian Ouguiya currency money fraction

## 2.5.0 - 2020-09-25
### Added
- Added Money::createFromMinorUnits to return Money object from minor units
- Added MoneyNormalizer::mapFromMinorUnits to map a Money object from minor units
### Deprecated
- Deprecated Money::createFromCents - more abstract Money::createFromMinorUnits is to be used instead 
- Deprecated MoneyNormalizer::mapFromCents - MoneyNormalizer::mapFromMinorUnits is to be used instead
## 2.4.3 - 2020-09-25
### Changed
- Changed Money::clearAmountValue does not use float anymore for higher precision

## 2.4.2 - 2020-09-23
### Changed
- Changed Money::clearAmountValue clearing capabilities

## 2.4.1 - 2020-08-14
### Changed
- Changed Money::clearAmountValue return from float to string

## 2.4.0 - 2020-07-28
### Changed
- Updated `XAU` fractions

## 2.3.1 - 2020-03-21
### Changed
- Updated `maba/monetary` version

## 2.3.0 - 2020-03-21
### Added
- Added `XAU`, `XAG`, `XPT` as currencies

### Changed
- `\Evp\Component\Money\MoneyException` will extend `\InvalidArgumentException` instead of `\Exception`
- `\Evp\Component\Money\MoneyException` will be thrown instead of `\InvalidArgumentException`

## 2.2.0
### Added
- Venezuelan bolivar currency money fraction

## 2.1.0
### Added
- Venezuelan bolivar currency iso number

## 2.0.0
### Changed
- MoneyNormalizer will now format amount taking into consideration the currency's decimal places.
If Money object was used with numbers beyond the currency's decimal places this will cause truncation. 
See an example below. 

Old behavior:
````
{
  "amount": "42.421234",
  "currency": "EUR"
}
````

New behavior:
````
{
  "amount": "42.42",
  "currency": "EUR"
}
````


## 1.6.2
### Added
- Released to public
