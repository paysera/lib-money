# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.4.0 - 2020-07-28
### Changed
- Updated `XAU`, `XPT` fractions

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
