<?php

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\Money;
use Evp\Component\Money\MoneyException;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * Test add
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     * @param Money $expected
     *
     * @dataProvider addProvider
     */
    public function testAdd(Money $operandOne, Money $operandTwo, Money $expected)
    {
        $this->assertEquals($expected, $operandOne->add($operandTwo));
    }

    /**
     * Test sub
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     * @param Money $expected
     *
     * @dataProvider subProvider
     */
    public function testSub(Money $operandOne, Money $operandTwo, Money $expected)
    {
        $this->assertEquals($expected, $operandOne->sub($operandTwo));
    }

    /**
     * Test mul
     *
     * @param Money $operandOne
     * @param string $multiplier
     * @param Money $expected
     *
     * @dataProvider mulProvider
     */
    public function testMul(Money $operandOne, $multiplier, Money $expected)
    {
        $this->assertEquals($expected, $operandOne->mul($multiplier));
    }

    /**
     * Test div
     *
     * @param Money $operandOne
     * @param string $divisor
     * @param Money $expected
     *
     * @dataProvider divProvider
     */
    public function testDiv(Money $operandOne, $divisor, Money $expected)
    {
        $this->assertEquals($expected, $operandOne->div($divisor));
    }

    /**
     * Test add exception
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider      addExceptionProvider
     */
    public function testAddException(Money $operandOne, Money $operandTwo)
    {
        $this->expectException(MoneyException::class);
        $operandOne->add($operandTwo);
    }

    /**
     * Test sub exception
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider      subExceptionProvider
     */
    public function testSubException(Money $operandOne, Money $operandTwo)
    {
        $this->expectException(MoneyException::class);
        $operandOne->sub($operandTwo);
    }

    /**
     * Test div exception with division of zero
     *
     * @param Money $operandOne
     *
     * @dataProvider      divExceptionProvider
     */
    public function testDivException(Money $operandOne)
    {
        $this->expectException(MoneyException::class);
        $operandOne->div(0);
    }

    /**
     * Test is equal
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider isEqualProvider
     */
    public function testIsEqual(Money $operandOne, Money $operandTwo)
    {
        $this->assertTrue($operandOne->isEqual($operandTwo));
    }

    /**
     * Test is greater
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider isGtProvider
     */
    public function testIsGt(Money $operandOne, Money $operandTwo)
    {
        $this->assertTrue($operandOne->isGt($operandTwo));
    }

    /**
     * Test is greater or equal
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider isGteProvider
     */
    public function testIsGte(Money $operandOne, Money $operandTwo)
    {
        $this->assertTrue($operandOne->isGte($operandTwo));
    }

    /**
     * Test is less
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider isLtProvider
     */
    public function testIsLt(Money $operandOne, Money $operandTwo)
    {
        $this->assertTrue($operandOne->isLt($operandTwo));
    }

    /**
     * Test is less or equal
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider isLteProvider
     */
    public function testIsLte(Money $operandOne, Money $operandTwo)
    {
        $this->assertTrue($operandOne->isLte($operandTwo));
    }

    /**
     * Test is greater exception
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider      isGtExceptionProvider
     */
    public function testIsGtException(Money $operandOne, Money $operandTwo)
    {
        $this->expectException(MoneyException::class);
        $operandOne->isGt($operandTwo);
    }

    /**
     * Test is greater or equal exception
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider      isGteExceptionProvider
     */
    public function testIsGteException(Money $operandOne, Money $operandTwo)
    {
        $this->expectException(MoneyException::class);
        $operandOne->isGte($operandTwo);
    }

    /**
     * Test is less exception
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider      isLtExceptionProvider
     */
    public function testIsLtException(Money $operandOne, Money $operandTwo)
    {
        $this->expectException(MoneyException::class);
        $operandOne->isLt($operandTwo);
    }

    /**
     * Test is less or equal exception
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider      isLteExceptionProvider
     */
    public function testIsLteException(Money $operandOne, Money $operandTwo)
    {
        $this->expectException(MoneyException::class);
        $operandOne->isLte($operandTwo);
    }

    /**
     * Test is same currency
     *
     * @param Money $operandOne
     * @param Money $operandTwo
     *
     * @dataProvider isSameCurrencyProvider
     */
    public function testIsSameCurrency(Money $operandOne, Money $operandTwo)
    {
        $this->assertTrue($operandOne->isSameCurrency($operandTwo));
    }

    /**
     * Test negate
     *
     * @param Money $operandOne
     * @param Money $expected
     *
     * @dataProvider negateProvider
     */
    public function testNegate(Money $operandOne, Money $expected)
    {
        $this->assertEquals($expected, $operandOne->negate());
    }

    /**
     * Test format amount
     *
     * @param Money $operandOne
     * @param int $precision
     * @param string $delimiter
     * @param string $thousandSeparator
     * @param string $expected
     *
     * @dataProvider formatAmountProvider
     */
    public function testFormatAmount(Money $operandOne, $precision, $delimiter, $thousandSeparator, $expected)
    {
        $this->assertSame($expected, $operandOne->formatAmount($precision, $delimiter, $thousandSeparator));
    }

    /**
     * Test ceil
     *
     * @param Money $operandOne
     * @param Money $expected
     * @param integer|null $precision
     *
     * @dataProvider ceilProvider
     */
    public function testCeil(Money $operandOne, Money $expected, $precision)
    {
        $this->assertEquals($expected, $operandOne->ceil($precision));
    }

    /**
     * Test floor
     *
     * @param Money $operandOne
     * @param Money $expected
     * @param integer|null $precision
     *
     * @dataProvider floorProvider
     */
    public function testFloor(Money $operandOne, Money $expected, $precision)
    {
        $this->assertEquals($expected, $operandOne->floor($precision));
    }

    /**
     * Test round
     *
     * @param Money $operandOne
     * @param Money $expected
     *
     * @dataProvider roundProvider
     */
    public function testRound(Money $operandOne, Money $expected)
    {
        $this->assertEquals($expected, $operandOne->round());
    }

    /**
     * Test getAsString
     *
     * @param Money $operandOne
     * @param string $expected
     *
     * @dataProvider getAsStringProvider
     */
    public function testGetAsString(Money $operandOne, $expected)
    {
        $this->assertSame($expected, (string)$operandOne);
    }

    /**
     * Test create from no delimiter amount
     *
     * @param string $operandOnemount
     * @param string $currency
     * @param Money $expected
     *
     * @dataProvider createFromNonDelimiterProvider
     */
    public function testCreateFromNonDelimiterAmount($operandOnemount, $currency, Money $expected)
    {
        $this->assertEquals($expected, Money::createFromNoDelimiterAmount($operandOnemount, $currency));
    }

    /**
     * Test create zero
     *
     * @param string $currency
     * @param Money $expected
     *
     * @dataProvider createZeroProvider
     */
    public function testCreateZero($currency, Money $expected)
    {
        $this->assertEquals($expected, Money::createZero($currency));
    }

    /**
     * Test amount does not change
     *
     * @param string $amount
     * @param string $currency
     *
     * @dataProvider sameAmountProvider
     */
    public function testAmountDoesNotChange($amount, $currency)
    {
        $this->assertSame($amount, Money::create($amount, $currency)->getAmount());
    }

    /**
     * Test
     *
     * @param string $amount
     * @param string $currency
     *
     * @dataProvider      setAmountExceptionProvider
     */
    public function testSetAmountException($amount, $currency)
    {
        $this->expectException(InvalidArgumentException::class);
        Money::create($amount, $currency);
    }

    /**
     * @param Money $money
     * @param string $expectedAmountInMinorUnits
     *
     * @dataProvider amountInMinorUnitsTestProvider
     */
    public function testGetAmountInMinorUnits(Money $money, $expectedAmountInMinorUnits)
    {
        $this->assertSame($money->getAmountInMinorUnits(), $expectedAmountInMinorUnits);
    }

    /**
     * @param Money $expectedMoney
     * @param string $amountInMinorUnits
     * @param string $currency
     *
     * @dataProvider createAmountInMinorUnitsTestProvider
     * @throws Exception
     */
    public function testCreateFromMinorUnits($expectedMoney, $amountInMinorUnits, $currency)
    {
        $this->assertEquals($expectedMoney, Money::createFromMinorUnits($amountInMinorUnits, $currency));
    }

    /**
     * Test unsupported currency
     *
     * @param int $amount
     * @param string $currency
     *
     * @dataProvider unsupportedCurrencyProvider
     */
    public function testUnsupportedCurrency($amount, $currency)
    {
        $this->expectException(InvalidArgumentException::class);
        new Money($amount, $currency);
    }

    /**
     *
     * @param string $amount
     * @param string $expectedResult
     *
     * @dataProvider clearAmountValueProvider
     */
    public function testClearAmountValue($amount, $expectedResult)
    {
        $this->assertSame($expectedResult, Money::clearAmountValue($amount));
    }

    /**
     * Add provider
     *
     * @return array
     */
    public function addProvider()
    {
        return array(
            array(new Money('1', 'EUR'), new Money('1', 'EUR'), new Money('2', 'EUR')),
            array(new Money('-1', 'EUR'), new Money('-1', 'EUR'), new Money('-2', 'EUR')),
            array(new Money('1', 'USD'), new Money('-1', 'USD'), new Money('0', 'USD')),
            array(new Money('-1', 'RUB'), new Money('1', 'RUB'), new Money('0', 'RUB')),
            array(new Money('0.5', 'EUR'), new Money('0.5', 'EUR'), new Money('1', 'EUR')),
            array(new Money('-0.5', 'EUR'), new Money('-0.5', 'EUR'), new Money('-1', 'EUR')),
            array(new Money('-0.5', 'RUB'), new Money('0', 'RUB'), new Money('-0.5', 'RUB')),
            array(new Money('-0', 'USD'), new Money('0', 'USD'), new Money('0', 'USD')),
        );
    }

    /**
     * Sub provider
     *
     * @return array
     */
    public function subProvider()
    {
        return array(
            array(new Money('1', 'EUR'), new Money('1', 'EUR'), new Money('0', 'EUR')),
            array(new Money('1', 'USD'), new Money('-1', 'USD'), new Money('2', 'USD')),
            array(new Money('-1', 'RUB'), new Money('1', 'RUB'), new Money('-2', 'RUB')),
            array(new Money('0.5', 'EUR'), new Money('0.5', 'EUR'), new Money('0', 'EUR')),
            array(new Money('-0.5', 'EUR'), new Money('-0.5', 'EUR'), new Money('0', 'EUR')),
            array(new Money('-0.5', 'RUB'), new Money('0', 'RUB'), new Money('-0.5', 'RUB')),
            array(new Money('-0', 'USD'), new Money('0', 'USD'), new Money('0', 'USD')),
        );
    }

    /**
     * Mul provider
     *
     * @return array
     */
    public function mulProvider()
    {
        return array(
            array(new Money('1', 'EUR'), '1', new Money('1', 'EUR')),
            array(new Money('-1', 'EUR'), '-1', new Money('1', 'EUR')),
            array(new Money('1', 'USD'), '-1', new Money('-1', 'USD')),
            array(new Money('-1', 'RUB'), '1', new Money('-1', 'RUB')),
            array(new Money('0.5', 'EUR'), '0.5', new Money('0.25', 'EUR')),
            array(new Money('-0.5', 'EUR'), '-0.5', new Money('0.25', 'EUR')),
            array(new Money('-0.5', 'RUB'), '0', new Money('0', 'RUB')),
            array(new Money('-0', 'USD'), '0', new Money('0', 'USD')),
        );
    }

    /**
     * Div provider
     *
     * @return array
     */
    public function divProvider()
    {
        return array(
            array(new Money('1', 'EUR'), '1', new Money('1', 'EUR')),
            array(new Money('1', 'EUR'), '2', new Money('0.5', 'EUR')),
            array(new Money('1', 'EUR'), '3', new Money('0.333333', 'EUR')),
            array(new Money('-1', 'EUR'), '-1', new Money('1', 'EUR')),
            array(new Money('1', 'USD'), '-1', new Money('-1', 'USD')),
            array(new Money('-1', 'RUB'), '1', new Money('-1', 'RUB')),
            array(new Money('0.5', 'EUR'), '0.5', new Money('1', 'EUR')),
            array(new Money('-0.5', 'EUR'), '-0.5', new Money('1', 'EUR')),
        );
    }

    /**
     * AddException provider
     *
     * @return array
     */
    public function addExceptionProvider()
    {
        return array(
            array(new Money('1', 'USD'), new Money('1', 'EUR')),
        );
    }

    /**
     * SubException provider
     *
     * @return array
     */
    public function subExceptionProvider()
    {
        return array(
            array(new Money('1', 'USD'), new Money('1', 'EUR')),
        );
    }

    /**
     * DivException provider
     *
     * @return array
     */
    public function divExceptionProvider()
    {
        return array(
            array(new Money('1', 'EUR')),
        );
    }

    /**
     * IsEqual provider
     *
     * @return array
     */
    public function isEqualProvider()
    {
        return array(
            array(new Money('1', 'EUR'), new Money('1', 'EUR')),
            array(new Money('1.0', 'EUR'), new Money('1', 'EUR')),
            array(new Money('1.00100', 'EUR'), new Money('1.0010', 'EUR')),
            array(new Money('-1', 'EUR'), new Money('-1', 'EUR')),
            array(new Money('-1.0', 'EUR'), new Money('-1', 'EUR')),
            array(new Money('-1.00100', 'EUR'), new Money('-1.00100', 'EUR')),
            array(new Money('1', 'EUR'), new Money('1', 'EUR')),
            array(new Money('0', 'USD'), new Money('0', 'EUR')),
            array(new Money('0.00', 'USD'), new Money('0', 'EUR')),
        );
    }

    /**
     * IsGt provider
     *
     * @return array
     */
    public function isGtProvider()
    {
        return array(
            array(new Money('0', 'EUR'), new Money('-1', 'EUR')),
            array(new Money('1', 'USD'), new Money('0', 'USD')),
            array(new Money('2', 'EUR'), new Money('1', 'EUR')),
            array(new Money('2', 'EUR'), new Money('1.999999', 'EUR')),
            array(new Money('1.00', 'EUR'), new Money('0.9999999', 'EUR')),
            array(new Money('1.00', 'EUR'), new Money('0.99999999999999999', 'EUR')),
        );
    }

    /**
     * IsGte provider
     *
     * @return array
     */
    public function isGteProvider()
    {
        return array(
            array(new Money('1', 'EUR'), new Money('1', 'EUR')),
            array(new Money('2', 'USD'), new Money('1', 'USD')),
        );
    }

    /**
     * IsLt provider
     *
     * @return array
     */
    public function isLtProvider()
    {
        return array(
            array(new Money('-1', 'EUR'), new Money('0', 'EUR')),
            array(new Money('0', 'USD'), new Money('1', 'USD')),
            array(new Money('1', 'EUR'), new Money('2', 'EUR')),
            array(new Money('0', 'EUR'), new Money('1', 'EUR')),
            array(new Money('5', 'EUR'), new Money('6.55', 'EUR')),
        );
    }

    /**
     * IsLte provider
     *
     * @return array
     */
    public function isLteProvider()
    {
        return array(
            array(new Money('-1', 'EUR'), new Money('0', 'EUR')),
            array(new Money('1', 'EUR'), new Money('1', 'EUR')),
            array(new Money('1.00', 'EUR'), new Money('1', 'EUR')),
            array(new Money('1.00100', 'EUR'), new Money('1.0010', 'EUR')),
            array(new Money('1', 'USD'), new Money('2', 'USD')),
        );
    }

    /**
     * IsGtException provider
     *
     * @return array
     */
    public function isGtExceptionProvider()
    {
        return array(
            array(new Money('1', 'USD'), new Money('1', 'EUR')),
        );
    }

    /**
     * IsGteException provider
     *
     * @return array
     */
    public function isGteExceptionProvider()
    {
        return array(
            array(new Money('1', 'USD'), new Money('1', 'EUR')),
        );
    }

    /**
     * IsLtException provider
     *
     * @return array
     */
    public function isLtExceptionProvider()
    {
        return array(
            array(new Money('1', 'USD'), new Money('1', 'EUR')),
        );
    }

    /**
     * IsLteException provider
     *
     * @return array
     */
    public function isLteExceptionProvider()
    {
        return array(
            array(new Money('1', 'USD'), new Money('1', 'EUR')),
        );
    }

    /**
     * IsSameCurrency provider
     *
     * @return array
     */
    public function isSameCurrencyProvider()
    {
        return array(
            array(new Money('1', 'EUR'), new Money('1', 'EUR')),
            array(new Money('1', 'EUR'), new Money('2', 'EUR')),
            array(new Money('3', 'EUR'), new Money('3', 'EUR')),
            array(new Money('1', 'USD'), new Money('1', 'USD')),
            array(new Money('1', 'USD'), new Money('-1.01', 'USD')),
            array(new Money('1', 'USD'), new Money('15', 'USD')),
        );
    }

    /**
     * Negate provider
     *
     * @return array
     */
    public function negateProvider()
    {
        return array(
            array(new Money('1', 'EUR'), new Money('-1', 'EUR')),
            array(new Money('-1', 'EUR'), new Money('1', 'EUR')),
            array(new Money('-1.9910', 'EUR'), new Money('1.991', 'EUR')),
            array(new Money('0', 'EUR'), new Money('0', 'EUR')),
        );
    }

    /**
     * FormatAmount provider
     *
     * @return array
     */
    public function formatAmountProvider()
    {
        return array(
            array(new Money('0', 'EUR'), 0, '.', null, '0'),
            array(new Money('0', 'EUR'), 1, '.', null, '0.0'),
            array(new Money('0', 'EUR'), 2, '.', null, '0.00'),
            array(new Money('0', 'EUR'), 2, '', null, '000'),

            array(new Money('1', 'EUR'), 0, '.', null, '1'),
            array(new Money('1', 'EUR'), 1, '.', null, '1.0'),
            array(new Money('1', 'EUR'), 2, '.', null, '1.00'),
            array(new Money('1', 'EUR'), 2, '', null, '100'),

            array(new Money('1.123', 'EUR'), 0, '.', null, '1'),
            array(new Money('1.123', 'EUR'), 1, '.', null, '1.1'),
            array(new Money('1.123', 'EUR'), 2, '.', null, '1.12'),
            array(new Money('1.123', 'EUR'), 2, '', null, '112'),
            array(new Money('1.123445', 'EUR'), 2, '.', null, '1.12'),
            array(new Money('1.120000', 'EUR'), 2, '.', null, '1.12'),

            array(new Money('12.3', 'EUR'), 0, '', null, '12'),
            array(new Money('12.3', 'EUR'), 1, '', null, '123'),
            array(new Money('12.3', 'EUR'), 2, '', null, '1230'),
            array(new Money('12.3', 'EUR'), 3, '', null, '12300'),
            array(new Money('12', 'EUR'), 2, '', null, '1200'),

            array(new Money('1200.3', 'EUR'), 3, '', null, '1200300'),
            array(new Money('1200', 'EUR'), 2, '', null, '120000'),
            array(new Money('1200.3', 'EUR'), 3, '', ',', '1,200300'),
            array(new Money('1200.3', 'EUR'), 3, '.', ',', '1,200.300'),
            array(new Money('1200.3', 'EUR'), 3, ':', '/', '1/200:300'),
            array(new Money('1200', 'EUR'), 2, '', ',', '1,20000'),

            array(new Money('1200.01', 'BYR'), 0, ',', null, '1200'),
        );
    }

    /**
     * Ceil provider
     *
     * @return array
     */
    public function ceilProvider()
    {
        return array(
            array(new Money('10.548', 'EUR'), new Money('10.55', 'EUR'), null),
            array(new Money('35', 'EUR'), new Money('35', 'EUR'), null),
            array(new Money('35.13', 'EUR'), new Money('35.13', 'EUR'), null),
            array(new Money('-10.548', 'EUR'), new Money('-10.54', 'EUR'), null),
            array(new Money('0.01', 'EUR'), new Money('0.01', 'EUR'), null),
            array(new Money('4.12', 'EUR'), new Money('5', 'EUR'), 0),
            array(new Money('8.88', 'EUR'), new Money('9', 'EUR'), 0),
        );
    }

    /**
     * Floor provider
     *
     * @return array
     */
    public function floorProvider()
    {
        return array(
            array(new Money('10.548', 'EUR'), new Money('10.54', 'EUR'), null),
            array(new Money('35', 'EUR'), new Money('35', 'EUR'), null),
            array(new Money('35.12', 'EUR'), new Money('35.12', 'EUR'), null),
            array(new Money('-10.548', 'EUR'), new Money('-10.55', 'EUR'), null),
            array(new Money('0.01', 'EUR'), new Money('0.01', 'EUR'), null),
            array(new Money('1.15', 'EUR'), new Money('1', 'EUR'), 0),
            array(new Money('2.99', 'EUR'), new Money('2', 'EUR'), 0),
        );
    }

    /**
     * Round provider
     *
     * @return array
     */
    public function roundProvider()
    {
        return array(
            array(new Money('10.544', 'EUR'), new Money('10.54', 'EUR')),
            array(new Money('-10.544', 'EUR'), new Money('-10.54', 'EUR')),
            array(new Money('10.545', 'EUR'), new Money('10.55', 'EUR')),
            array(new Money('-10.545', 'EUR'), new Money('-10.55', 'EUR')),
        );
    }

    /**
     * GetAsString provider
     *
     * @return array
     */
    public function getAsStringProvider()
    {
        return array(
            array(new Money('1', 'EUR'), '1.00 EUR'),
            array(new Money('1.123456', 'EUR'), '1.12 EUR'),
            array(new Money('1.120000', 'EUR'), '1.12 EUR'),
            array(new Money('-1.120001', 'EUR'), '-1.12 EUR'),
        );
    }

    /**
     * CreateFromNonDelimiter provider
     *
     * @return array
     */
    public function createFromNonDelimiterProvider()
    {
        return array(
            array('100', 'EUR', new Money('1', 'EUR')),
            array('0', 'EUR', new Money('0', 'EUR'))
        );
    }

    /**
     * CreateZero provider
     *
     * @return array
     */
    public function createZeroProvider()
    {
        return array(
            array('USD', new Money('0', 'USD')),
            array('EUR', new Money('0', 'EUR')),
        );
    }

    /**
     * Same amount provider
     *
     * @return array
     */
    public function sameAmountProvider()
    {
        return array(
            array('0', 'EUR'),
            array('-0', 'EUR'),
            array('+0', 'EUR'),
            array('-0.000', 'EUR'),
            array('+0.000', 'EUR'),
            array('0.123', 'EUR'),
            array('-0', 'EUR'),
            array('+0', 'EUR'),
            array('123.991', 'EUR'),
            array('12.21', 'EUR'),
            array('12', 'EUR'),
            array('-12', 'EUR'),
            array('+12', 'EUR'),
            array('12.000000', 'EUR'),
        );
    }

    /**
     * Set amount exception provider
     *
     * @return array
     */
    public function setAmountExceptionProvider()
    {
        return array(
            array('0+', 'EUR'),
            array('0-', 'EUR'),
            array('0.00+', 'EUR'),
            array('0.00-', 'EUR'),
            array('0.0+001', 'EUR'),
            array('0.0-001', 'EUR'),
            array('1+.-1', 'EUR'),
            array('1-1', 'EUR'),
            array('1.', 'EUR'),
            array('10.', 'EUR'),
            array('0LTL', 'EUR'),
            array('0.00LTL', 'EUR'),
            array('0.0EUR001', 'EUR'),
            array('LTL', 'EUR'),
            array('.0', 'EUR'),
            array('U.0', 'EUR'),
            array('-1.E1', 'EUR'),
            array('+1.100000000000E1', 'EUR'),
            array('1.22222222222222E+E', 'EUR'),
            array('+1.100000000000e1', 'EUR'),
            array('1.22222222222222e+e', 'EUR'),
        );
    }

    public function amountInMinorUnitsTestProvider()
    {
        return array(
            array(new Money('1', 'EUR'), 100),
            array(new Money('-1', 'EUR'), -100),
            array(new Money('1', 'BYR'), 1),
            array(new Money('-1', 'BYR'), -1),
            array(new Money('1', 'MGA'), 10),
            array(new Money('-1', 'MGA'), -10),
            array(new Money('1', 'KWD'), 1000),
            array(new Money('-1', 'KWD'), -1000),
            array(new Money('1', 'XAU'), 100000),
            array(new Money('-1', 'XAU'), -100000),
        );
    }

    public function createAmountInMinorUnitsTestProvider()
    {
        return array(
            array(new Money('0.01', 'EUR'), 1, 'EUR'),
            array(new Money('-0.01', 'EUR'), -1, 'EUR'),
            array(new Money('1', 'JPY'), 1, 'JPY'),
            array(new Money('-1', 'JPY'), -1, 'JPY'),
            array(new Money('0.100000', 'MGA'), 1, 'MGA'),
            array(new Money('-0.100000', 'MGA'), -1, 'MGA'),
            array(new Money('0.001000', 'KWD'), 1, 'KWD'),
            array(new Money('-0.001000', 'KWD'), -1, 'KWD'),
            array(new Money('0.000010', 'XAU'), 1, 'XAU'),
            array(new Money('-0.000010', 'XAU'), -1, 'XAU'),
        );
    }

    /**
     * UnsupportedCurrency provider
     *
     * @return array
     */
    public function unsupportedCurrencyProvider()
    {
        return array(
            array(1, 'XXX'),
            array(null, 'XXX'),
        );
    }

    /**
     * @param string $expectedAmount
     * @param string $inputAmount
     *
     * @dataProvider absProvider
     */
    public function testAbs($expectedAmount, $inputAmount)
    {
        $money = new Money($inputAmount, 'EUR');
        $this->assertSame($expectedAmount, $money->abs()->getAmount());
    }

    public function absProvider()
    {
        return array(
            array('0.000005', '0.000005'),
            array('0.000005', '-0.000005'),
        );
    }

    public function clearAmountValueProvider()
    {
        return array(
            array('1.', '1'),
            array('.1', '0.1'),
            array('1', '1'),
            array('1..1', '1.1'),
            array('0.00001', '0.00001'),
            array('0.000000000000001', '0.000000000000001'),
            array('1233.123456778742433245632234234', '1233.123456778742433245632234234'),
            array('123456778742433245632234234.00000122223213', '123456778742433245632234234.00000122223213'),
            array('10 000,01', '10000.01'),
            array('10.000,01', '10000.01'),
            array('10,000.01', '10000.01'),
            array('10.000.000,01', '10000000.01'),
            array('10,000,000.01', '10000000.01'),
            array('11111111111111.111111', '11111111111111.111111'),
        );
    }
}
