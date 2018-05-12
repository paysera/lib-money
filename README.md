PHP Library for Operations with Money [![Build Status](https://travis-ci.org/paysera/lib-money.svg?branch=master)](https://travis-ci.org/paysera/lib-money)
====

Instead of storing prices, profit and any other amount of money in float or integer variable, use Money value object
instead. It groups amount with currency, as amount without currency does not give much information.

## Installing

```shell
composer require evp/money
```

### Why not floats?

 - Floats are not reliable for making operations with money as they can loose precision and cents can get lost.

### Why not integers?

 - When max integer value is reached, crazy things can happen: it can be truncated or even get negative.
 These types of errors can be critical, especially when working with money.
 On 32bit systems (and any system on Windows) max int value is 2147483647. As this is enough for most cases, some
 currencies are relatively very small compared to others (for example, 2,147,483,647.00 BYR is about 150,000.00 EUR).
 - There might be cases, where money should be calculated or even saved without rounding to smallest available units.
 For example, very small commissions (parts of cent), which can add to a big amount when large number of them are
 added.
 - When storing amount as integer, you must always take into account the currency divisor
 (smallest available unit in that currency) before outputting the result or any other operation. For example, most currencies
 have cents as their smallest unit while smallest unit for Bahraini Dinar is 0.001 and Japanese Yen has no cents at all.
 Alternatively, if you always store units as cents, you cannot represent smallest units of some currencies.

### Architecture

`Money` is value object - it's immutable. In other words, if you need to change the amount or currency, just create
another `Money` object. The same `Money` object can be referenced in several places, so changing only the fields
of this object could unintentionally change money amount or currency in some other place.

## Api
`Money` class provides self-contained logic for arithmetic and comparison operations with other
`Money` instances. All arithmetic operations returns new `Money` instance.

* `Money::add` - adds current Money amount to given; throws exception if currencies are different.
* `Money::sub` - subtracts given Money amount from current Money amount; throws exception if currencies are different.
* `Money::mul` - multiplies current money amount by given multiplier.
* `Money::div` - divides current money amount by given divisor.
* `Money::negate` - negates current money amount.
* `Money::round` - rounds current money amount to given number of decimal places; if no precision is given, rounds to maximum precision for current currency.
* `Money::ceil` - rounds current money amount to ceil; if no precision is given, rounds to maximum precision for current currency.
* `Money::floor` - rounds current money amount to floor; if no precision is given, rounds to maximum precision for current currency.
* `Money::check` - checks if current amount is not too small; throws exception if so.
* `Money::isGt` - tells if current money is greater than given.
* `Money::isGte` - tells if current money is greater or equal than given.
* `Money::isLt` - tells if current money is less than given.
* `Money::isLte` - tells if current money is less or equal than given.
* `Money::isEqual` - tells if current money is equal to given.
* `Money::isNegative` - tells if current money is negative.
* `Money::isPositive` - tells if current money is positive.
* `Money::isZero` - tells if current money has zero amount.
* `Money::isSameCurrency` - tells if current money has same currency as given.
* `Money::abs` - returns money with absolute amount.
* `Money::getArrayRepresentation` - returns array with keys `amount` and `currency`.
* `Money::getAsString` - returns string with concatenated `amount` and `currency` separated by space.
* `Money::getFraction` - returns number of decimal places supported by given currency.
 
## Usage

```php
$money = new Money(1, 'EUR');
$timesTwo = $money->mul(2);

$money->isLt($timesTwo); // true
```

