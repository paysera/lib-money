# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

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
