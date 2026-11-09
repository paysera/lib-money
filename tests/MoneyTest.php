<?php

declare(strict_types=1);

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\Money;
use Evp\Component\Money\MoneyException;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

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
        return [
            [new Money('1', 'EUR'), new Money('1', 'EUR'), new Money('2', 'EUR')],
            [new Money('-1', 'EUR'), new Money('-1', 'EUR'), new Money('-2', 'EUR')],
            [new Money('1', 'USD'), new Money('-1', 'USD'), new Money('0', 'USD')],
            [new Money('-1', 'RUB'), new Money('1', 'RUB'), new Money('0', 'RUB')],
            [new Money('0.5', 'EUR'), new Money('0.5', 'EUR'), new Money('1', 'EUR')],
            [new Money('-0.5', 'EUR'), new Money('-0.5', 'EUR'), new Money('-1', 'EUR')],
            [new Money('-0.5', 'RUB'), new Money('0', 'RUB'), new Money('-0.5', 'RUB')],
            [new Money('-0', 'USD'), new Money('0', 'USD'), new Money('0', 'USD')],
        ];
    }

    /**
     * Sub provider
     *
     * @return array
     */
    public function subProvider()
    {
        return [
            [new Money('1', 'EUR'), new Money('1', 'EUR'), new Money('0', 'EUR')],
            [new Money('1', 'USD'), new Money('-1', 'USD'), new Money('2', 'USD')],
            [new Money('-1', 'RUB'), new Money('1', 'RUB'), new Money('-2', 'RUB')],
            [new Money('0.5', 'EUR'), new Money('0.5', 'EUR'), new Money('0', 'EUR')],
            [new Money('-0.5', 'EUR'), new Money('-0.5', 'EUR'), new Money('0', 'EUR')],
            [new Money('-0.5', 'RUB'), new Money('0', 'RUB'), new Money('-0.5', 'RUB')],
            [new Money('-0', 'USD'), new Money('0', 'USD'), new Money('0', 'USD')],
        ];
    }

    /**
     * Mul provider
     *
     * @return array
     */
    public function mulProvider()
    {
        return [
            [new Money('1', 'EUR'), '1', new Money('1', 'EUR')],
            [new Money('-1', 'EUR'), '-1', new Money('1', 'EUR')],
            [new Money('1', 'USD'), '-1', new Money('-1', 'USD')],
            [new Money('-1', 'RUB'), '1', new Money('-1', 'RUB')],
            [new Money('0.5', 'EUR'), '0.5', new Money('0.25', 'EUR')],
            [new Money('-0.5', 'EUR'), '-0.5', new Money('0.25', 'EUR')],
            [new Money('-0.5', 'RUB'), '0', new Money('0', 'RUB')],
            [new Money('-0', 'USD'), '0', new Money('0', 'USD')],
        ];
    }

    /**
     * Div provider
     *
     * @return array
     */
    public function divProvider()
    {
        return [
            [new Money('1', 'EUR'), '1', new Money('1', 'EUR')],
            [new Money('1', 'EUR'), '2', new Money('0.5', 'EUR')],
            [new Money('1', 'EUR'), '3', new Money('0.333333', 'EUR')],
            [new Money('-1', 'EUR'), '-1', new Money('1', 'EUR')],
            [new Money('1', 'USD'), '-1', new Money('-1', 'USD')],
            [new Money('-1', 'RUB'), '1', new Money('-1', 'RUB')],
            [new Money('0.5', 'EUR'), '0.5', new Money('1', 'EUR')],
            [new Money('-0.5', 'EUR'), '-0.5', new Money('1', 'EUR')],
        ];
    }

    /**
     * AddException provider
     *
     * @return array
     */
    public function addExceptionProvider()
    {
        return [
            [new Money('1', 'USD'), new Money('1', 'EUR')],
        ];
    }

    /**
     * SubException provider
     *
     * @return array
     */
    public function subExceptionProvider()
    {
        return [
            [new Money('1', 'USD'), new Money('1', 'EUR')],
        ];
    }

    /**
     * DivException provider
     *
     * @return array
     */
    public function divExceptionProvider()
    {
        return [
            [new Money('1', 'EUR')],
        ];
    }

    /**
     * IsEqual provider
     *
     * @return array
     */
    public function isEqualProvider()
    {
        return [
            [new Money('1', 'EUR'), new Money('1', 'EUR')],
            [new Money('1.0', 'EUR'), new Money('1', 'EUR')],
            [new Money('1.00100', 'EUR'), new Money('1.0010', 'EUR')],
            [new Money('-1', 'EUR'), new Money('-1', 'EUR')],
            [new Money('-1.0', 'EUR'), new Money('-1', 'EUR')],
            [new Money('-1.00100', 'EUR'), new Money('-1.00100', 'EUR')],
            [new Money('1', 'EUR'), new Money('1', 'EUR')],
            [new Money('0', 'USD'), new Money('0', 'EUR')],
            [new Money('0.00', 'USD'), new Money('0', 'EUR')],
        ];
    }

    /**
     * IsGt provider
     *
     * @return array
     */
    public function isGtProvider()
    {
        return [
            [new Money('0', 'EUR'), new Money('-1', 'EUR')],
            [new Money('1', 'USD'), new Money('0', 'USD')],
            [new Money('2', 'EUR'), new Money('1', 'EUR')],
            [new Money('2', 'EUR'), new Money('1.999999', 'EUR')],
            [new Money('1.00', 'EUR'), new Money('0.9999999', 'EUR')],
            [new Money('1.00', 'EUR'), new Money('0.99999999999999999', 'EUR')],
        ];
    }

    /**
     * IsGte provider
     *
     * @return array
     */
    public function isGteProvider()
    {
        return [
            [new Money('1', 'EUR'), new Money('1', 'EUR')],
            [new Money('2', 'USD'), new Money('1', 'USD')],
        ];
    }

    /**
     * IsLt provider
     *
     * @return array
     */
    public function isLtProvider()
    {
        return [
            [new Money('-1', 'EUR'), new Money('0', 'EUR')],
            [new Money('0', 'USD'), new Money('1', 'USD')],
            [new Money('1', 'EUR'), new Money('2', 'EUR')],
            [new Money('0', 'EUR'), new Money('1', 'EUR')],
            [new Money('5', 'EUR'), new Money('6.55', 'EUR')],
        ];
    }

    /**
     * IsLte provider
     *
     * @return array
     */
    public function isLteProvider()
    {
        return [
            [new Money('-1', 'EUR'), new Money('0', 'EUR')],
            [new Money('1', 'EUR'), new Money('1', 'EUR')],
            [new Money('1.00', 'EUR'), new Money('1', 'EUR')],
            [new Money('1.00100', 'EUR'), new Money('1.0010', 'EUR')],
            [new Money('1', 'USD'), new Money('2', 'USD')],
        ];
    }

    /**
     * IsGtException provider
     *
     * @return array
     */
    public function isGtExceptionProvider()
    {
        return [
            [new Money('1', 'USD'), new Money('1', 'EUR')],
        ];
    }

    /**
     * IsGteException provider
     *
     * @return array
     */
    public function isGteExceptionProvider()
    {
        return [
            [new Money('1', 'USD'), new Money('1', 'EUR')],
        ];
    }

    /**
     * IsLtException provider
     *
     * @return array
     */
    public function isLtExceptionProvider()
    {
        return [
            [new Money('1', 'USD'), new Money('1', 'EUR')],
        ];
    }

    /**
     * IsLteException provider
     *
     * @return array
     */
    public function isLteExceptionProvider()
    {
        return [
            [new Money('1', 'USD'), new Money('1', 'EUR')],
        ];
    }

    /**
     * IsSameCurrency provider
     *
     * @return array
     */
    public function isSameCurrencyProvider()
    {
        return [
            [new Money('1', 'EUR'), new Money('1', 'EUR')],
            [new Money('1', 'EUR'), new Money('2', 'EUR')],
            [new Money('3', 'EUR'), new Money('3', 'EUR')],
            [new Money('1', 'USD'), new Money('1', 'USD')],
            [new Money('1', 'USD'), new Money('-1.01', 'USD')],
            [new Money('1', 'USD'), new Money('15', 'USD')],
        ];
    }

    /**
     * Negate provider
     *
     * @return array
     */
    public function negateProvider()
    {
        return [
            [new Money('1', 'EUR'), new Money('-1', 'EUR')],
            [new Money('-1', 'EUR'), new Money('1', 'EUR')],
            [new Money('-1.9910', 'EUR'), new Money('1.991', 'EUR')],
            [new Money('0', 'EUR'), new Money('0', 'EUR')],
        ];
    }

    /**
     * FormatAmount provider
     *
     * @return array
     */
    public function formatAmountProvider()
    {
        return [
            [new Money('0', 'EUR'), 0, '.', null, '0'],
            [new Money('0', 'EUR'), 1, '.', null, '0.0'],
            [new Money('0', 'EUR'), 2, '.', null, '0.00'],
            [new Money('0', 'EUR'), 2, '', null, '000'],

            [new Money('1', 'EUR'), 0, '.', null, '1'],
            [new Money('1', 'EUR'), 1, '.', null, '1.0'],
            [new Money('1', 'EUR'), 2, '.', null, '1.00'],
            [new Money('1', 'EUR'), 2, '', null, '100'],

            [new Money('1.123', 'EUR'), 0, '.', null, '1'],
            [new Money('1.123', 'EUR'), 1, '.', null, '1.1'],
            [new Money('1.123', 'EUR'), 2, '.', null, '1.12'],
            [new Money('1.123', 'EUR'), 2, '', null, '112'],
            [new Money('1.123445', 'EUR'), 2, '.', null, '1.12'],
            [new Money('1.120000', 'EUR'), 2, '.', null, '1.12'],

            [new Money('12.3', 'EUR'), 0, '', null, '12'],
            [new Money('12.3', 'EUR'), 1, '', null, '123'],
            [new Money('12.3', 'EUR'), 2, '', null, '1230'],
            [new Money('12.3', 'EUR'), 3, '', null, '12300'],
            [new Money('12', 'EUR'), 2, '', null, '1200'],

            [new Money('1200.3', 'EUR'), 3, '', null, '1200300'],
            [new Money('1200', 'EUR'), 2, '', null, '120000'],
            [new Money('1200.3', 'EUR'), 3, '', ',', '1,200300'],
            [new Money('1200.3', 'EUR'), 3, '.', ',', '1,200.300'],
            [new Money('1200.3', 'EUR'), 3, ':', '/', '1/200:300'],
            [new Money('1200', 'EUR'), 2, '', ',', '1,20000'],

            [new Money('1200.01', 'BYR'), 0, ',', null, '1200'],
        ];
    }

    /**
     * Ceil provider
     *
     * @return array
     */
    public function ceilProvider()
    {
        return [
            [new Money('10.548', 'EUR'), new Money('10.55', 'EUR'), null],
            [new Money('35', 'EUR'), new Money('35', 'EUR'), null],
            [new Money('35.13', 'EUR'), new Money('35.13', 'EUR'), null],
            [new Money('-10.548', 'EUR'), new Money('-10.54', 'EUR'), null],
            [new Money('0.01', 'EUR'), new Money('0.01', 'EUR'), null],
            [new Money('4.12', 'EUR'), new Money('5', 'EUR'), 0],
            [new Money('8.88', 'EUR'), new Money('9', 'EUR'), 0],
        ];
    }

    /**
     * Floor provider
     *
     * @return array
     */
    public function floorProvider()
    {
        return [
            [new Money('10.548', 'EUR'), new Money('10.54', 'EUR'), null],
            [new Money('35', 'EUR'), new Money('35', 'EUR'), null],
            [new Money('35.12', 'EUR'), new Money('35.12', 'EUR'), null],
            [new Money('-10.548', 'EUR'), new Money('-10.55', 'EUR'), null],
            [new Money('0.01', 'EUR'), new Money('0.01', 'EUR'), null],
            [new Money('1.15', 'EUR'), new Money('1', 'EUR'), 0],
            [new Money('2.99', 'EUR'), new Money('2', 'EUR'), 0],
        ];
    }

    /**
     * Round provider
     *
     * @return array
     */
    public function roundProvider()
    {
        return [
            [new Money('10.544', 'EUR'), new Money('10.54', 'EUR')],
            [new Money('-10.544', 'EUR'), new Money('-10.54', 'EUR')],
            [new Money('10.545', 'EUR'), new Money('10.55', 'EUR')],
            [new Money('-10.545', 'EUR'), new Money('-10.55', 'EUR')],
        ];
    }

    /**
     * GetAsString provider
     *
     * @return array
     */
    public function getAsStringProvider()
    {
        return [
            [new Money('1', 'EUR'), '1.00 EUR'],
            [new Money('1.123456', 'EUR'), '1.12 EUR'],
            [new Money('1.120000', 'EUR'), '1.12 EUR'],
            [new Money('-1.120001', 'EUR'), '-1.12 EUR'],
        ];
    }

    /**
     * CreateFromNonDelimiter provider
     *
     * @return array
     */
    public function createFromNonDelimiterProvider()
    {
        return [
            ['100', 'EUR', new Money('1', 'EUR')],
            ['0', 'EUR', new Money('0', 'EUR')],
        ];
    }

    /**
     * CreateZero provider
     *
     * @return array
     */
    public function createZeroProvider()
    {
        return [
            ['USD', new Money('0', 'USD')],
            ['EUR', new Money('0', 'EUR')],
        ];
    }

    /**
     * Same amount provider
     *
     * @return array
     */
    public function sameAmountProvider()
    {
        return [
            ['0', 'EUR'],
            ['-0', 'EUR'],
            ['+0', 'EUR'],
            ['-0.000', 'EUR'],
            ['+0.000', 'EUR'],
            ['0.123', 'EUR'],
            ['-0', 'EUR'],
            ['+0', 'EUR'],
            ['123.991', 'EUR'],
            ['12.21', 'EUR'],
            ['12', 'EUR'],
            ['-12', 'EUR'],
            ['+12', 'EUR'],
            ['12.000000', 'EUR'],
        ];
    }

    /**
     * Set amount exception provider
     *
     * @return array
     */
    public function setAmountExceptionProvider()
    {
        return [
            ['0+', 'EUR'],
            ['0-', 'EUR'],
            ['0.00+', 'EUR'],
            ['0.00-', 'EUR'],
            ['0.0+001', 'EUR'],
            ['0.0-001', 'EUR'],
            ['1+.-1', 'EUR'],
            ['1-1', 'EUR'],
            ['1.', 'EUR'],
            ['10.', 'EUR'],
            ['0LTL', 'EUR'],
            ['0.00LTL', 'EUR'],
            ['0.0EUR001', 'EUR'],
            ['LTL', 'EUR'],
            ['.0', 'EUR'],
            ['U.0', 'EUR'],
            ['-1.E1', 'EUR'],
            ['+1.100000000000E1', 'EUR'],
            ['1.22222222222222E+E', 'EUR'],
            ['+1.100000000000e1', 'EUR'],
            ['1.22222222222222e+e', 'EUR'],
        ];
    }

    public function amountInMinorUnitsTestProvider()
    {
        return [
            [new Money('1', 'EUR'), 100],
            [new Money('-1', 'EUR'), -100],
            [new Money('1', 'BYR'), 1],
            [new Money('-1', 'BYR'), -1],
            [new Money('1', 'MGA'), 10],
            [new Money('-1', 'MGA'), -10],
            [new Money('1', 'KWD'), 1000],
            [new Money('-1', 'KWD'), -1000],
            [new Money('1', 'XAU'), 100000],
            [new Money('-1', 'XAU'), -100000],
        ];
    }

    public function createAmountInMinorUnitsTestProvider()
    {
        return [
            [new Money('0.01', 'EUR'), 1, 'EUR'],
            [new Money('-0.01', 'EUR'), -1, 'EUR'],
            [new Money('1', 'JPY'), 1, 'JPY'],
            [new Money('-1', 'JPY'), -1, 'JPY'],
            [new Money('0.100000', 'MGA'), 1, 'MGA'],
            [new Money('-0.100000', 'MGA'), -1, 'MGA'],
            [new Money('0.001000', 'KWD'), 1, 'KWD'],
            [new Money('-0.001000', 'KWD'), -1, 'KWD'],
            [new Money('0.000010', 'XAU'), 1, 'XAU'],
            [new Money('-0.000010', 'XAU'), -1, 'XAU'],
        ];
    }

    /**
     * UnsupportedCurrency provider
     *
     * @return array
     */
    public function unsupportedCurrencyProvider()
    {
        return [
            [1, 'XXX'],
            [null, 'XXX'],
        ];
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
        return [
            ['0.000005', '0.000005'],
            ['0.000005', '-0.000005'],
        ];
    }

    public function clearAmountValueProvider()
    {
        return [
            ['1.', '1'],
            ['.1', '0.1'],
            ['1', '1'],
            ['1..1', '1.1'],
            ['0.00001', '0.00001'],
            ['0.000000000000001', '0.000000000000001'],
            ['1233.123456778742433245632234234', '1233.123456778742433245632234234'],
            ['123456778742433245632234234.00000122223213', '123456778742433245632234234.00000122223213'],
            ['10 000,01', '10000.01'],
            ['10.000,01', '10000.01'],
            ['10,000.01', '10000.01'],
            ['10.000.000,01', '10000000.01'],
            ['10,000,000.01', '10000000.01'],
            ['11111111111111.111111', '11111111111111.111111'],
        ];
    }

    /**
     * @dataProvider commaDecimalSeparatorProvider
     */
    public function testACommaThatCanOnlyBeADecimalSeparatorIsAccepted(string $amount, string $expected)
    {
        $this->assertSame($expected, (new Money($amount, 'EUR'))->getAmount());
    }

    /**
     * @return array[]
     */
    public function commaDecimalSeparatorProvider()
    {
        return [
            'two decimals' => ['10,50', '10.50'],
            'two zero decimals' => ['670,00', '670.00'],
            'seven integer digits' => ['1000000,00', '1000000.00'],
            'one decimal' => ['1,5', '1.5'],
            'six decimals' => ['1,010000', '1.010000'],
            'three decimals after four integer digits' => ['1234,567', '1234.567'],
            'three decimals after a zero' => ['0,500', '0.500'],
            'negative three decimals after a zero' => ['-0,125', '-0.125'],
            'four decimals' => ['1,0000', '1.0000'],
        ];
    }

    /**
     * @dataProvider strayCharactersProvider
     */
    public function testAnAmountWithStrayCharactersIsRejected(string $amount)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Amount has invalid chars');

        new Money($amount, 'EUR');
    }

    /**
     * @return array[]
     */
    public function strayCharactersProvider()
    {
        return [
            'trailing line feed' => ["12.34\n"],
            'whole number with trailing line feed' => ["1000\n"],
            'negative with trailing line feed' => ["-12.34\n"],
            'explicit plus with trailing line feed' => ["+5\n"],
            'decimal comma with trailing line feed' => ["12,34\n"],
            'negative decimal comma with trailing line feed' => ["-1,5\n"],
            'two trailing line feeds' => ["12.34\n\n"],
            'trailing carriage return' => ["12.34\r"],
            'trailing carriage return and line feed' => ["12.34\r\n"],
            'leading line feed' => ["\n12.34"],
            'line feed inside' => ["12\n.34"],
            'trailing dot with line feed' => ["12.\n"],
            'sign only with line feed' => ["-\n"],
            'line feed only' => ["\n"],
            'leading tab' => ["\t12.34"],
            'trailing space' => ['12.34 '],
            'leading space' => [' 12.34'],
            'trailing vertical tab' => ["12.34\v"],
            'trailing form feed' => ["12.34\f"],
            'trailing null byte' => ["12.34\0"],
        ];
    }

    /**
     * @dataProvider escapedRejectionMessageProvider
     */
    public function testTheRejectionMessageEscapesControlCharacters(string $amount, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage($message);

        new Money($amount, 'EUR');
    }

    /**
     * @return array[]
     */
    public function escapedRejectionMessageProvider()
    {
        return [
            'line feed' => ["12.34\n", 'Amount has invalid chars: 12.34\n'],
            'backslash' => ['1\\2', 'Amount has invalid chars: 1\\\\2'],
            'delete character' => ["12\x7f", 'Amount has invalid chars: 12\177'],
        ];
    }

    public function testCreateRejectsAnAmountWithALineEnding()
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Amount has invalid chars');

        Money::create("12.34\n", 'EUR');
    }

    /**
     * @param int|float $amount
     *
     * @dataProvider numericAmountProvider
     */
    public function testNumericAmountsAreStillAccepted($amount, string $expected)
    {
        $this->assertSame($expected, (new Money($amount, 'EUR'))->getAmount());
    }

    /**
     * @return array[]
     */
    public function numericAmountProvider()
    {
        return [
            'integer' => [1000, '1000'],
            'negative integer' => [-3, '-3'],
            'zero' => [0, '0'],
            'float' => [12.5, '12.5'],
            'small float' => [0.1, '0.1'],
        ];
    }

    /**
     * @dataProvider validFactoryInputProvider
     */
    public function testFactoriesStillAcceptValidInput(string $method, string $amount, string $currency, string $expected)
    {
        $this->assertSame($expected, Money::$method($amount, $currency)->getAmount());
    }

    /**
     * @return array[]
     */
    public function validFactoryInputProvider()
    {
        return [
            'minor units' => ['createFromMinorUnits', '1234', 'EUR', '12.340000'],
            'minor units without decimals' => ['createFromMinorUnits', '1234', 'JPY', '1234.000000'],
            'no delimiter amount' => ['createFromNoDelimiterAmount', '1234', 'EUR', '12.34'],
            'no delimiter amount with a plus sign' => ['createFromNoDelimiterAmount', '+1234', 'EUR', '12.34'],
            'one-digit no delimiter amount with a plus sign' => ['createFromNoDelimiterAmount', '+5', 'EUR', '0.05'],
            'two-digit no delimiter amount with a plus sign' => ['createFromNoDelimiterAmount', '+12', 'EUR', '0.12'],
        ];
    }

    /**
     * @dataProvider invalidNoDelimiterAmountProvider
     */
    public function testCreateFromNoDelimiterAmountReportsTheRawValue(string $amount, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage($message);

        Money::createFromNoDelimiterAmount($amount, 'EUR');
    }

    /**
     * @return array[]
     */
    public function invalidNoDelimiterAmountProvider()
    {
        return [
            'a decimal point' => ['12.34', 'Invalid amount without delimiter: 12.34'],
            'a leading space' => [' 1234', 'Invalid amount without delimiter:  1234'],
            'a plus sign only' => ['+', 'Invalid amount without delimiter: +'],
            'a minus sign only' => ['-', 'Invalid amount without delimiter: -'],
        ];
    }

    /**
     * @dataProvider noDelimiterAmountWithoutFractionProvider
     */
    public function testCreateFromNoDelimiterAmountWithoutFractionKeepsEveryDigit(string $amount, string $expected)
    {
        $this->assertSame($expected, Money::createFromNoDelimiterAmount($amount, 'JPY', 0)->getAmount());
    }

    /**
     * @return array[]
     */
    public function noDelimiterAmountWithoutFractionProvider()
    {
        return [
            'positive' => ['1234', '1234'],
            'negative' => ['-5', '-5'],
            'leading zeros' => ['0012', '12'],
        ];
    }

    /**
     * @param mixed $operand
     *
     * @dataProvider validOperandProvider
     */
    public function testMulAndDivAcceptANumericOperand(string $method, $operand, string $expected)
    {
        $this->assertSame($expected, (new Money('10', 'EUR'))->$method($operand)->getAmount());
    }

    /**
     * @return array[]
     */
    public function validOperandProvider()
    {
        return [
            'integer multiplier' => ['mul', 2, '20.000000'],
            'float multiplier' => ['mul', 0.5, '5.000000'],
            'multiplier without a leading digit' => ['mul', '.5', '5.000000'],
            'multiplier with a trailing dot' => ['mul', '5.', '50.000000'],
            'negative zero multiplier' => ['mul', -0.0, '0.000000'],
            'integer divisor' => ['div', 4, '2.500000'],
            'divisor without a leading digit' => ['div', '.5', '20.000000'],
            'null multiplier counts as zero' => ['mul', null, '0.000000'],
            'false multiplier counts as zero' => ['mul', false, '0.000000'],
            'empty string multiplier counts as zero' => ['mul', '', '0.000000'],
            'float multiplier below 1e-4' => ['mul', 0.000035, '0.000350'],
            'float multiplier in scientific notation' => ['mul', 1.0E-5, '0.000100'],
            'large float multiplier' => ['mul', 1.0E+20, '1000000000000000000000.000000'],
            'float divisor below 1e-4' => ['div', 0.000035, '285714.285714'],
            'true multiplier' => ['mul', true, '10.000000'],
            'true divisor' => ['div', true, '10.000000'],
            'multiplier with a plus sign' => ['mul', '+5', '50.000000'],
            'divisor with a plus sign' => ['div', '+5', '2.000000'],
            'negative zero string multiplier' => ['mul', '-0.0', '0.000000'],
            'negative multiplier without a leading digit' => ['mul', '-.5', '-5.000000'],
            'negative integer divisor' => ['div', -4, '-2.500000'],
            'largest integer multiplier' => ['mul', PHP_INT_MAX, '92233720368547758070.000000'],
            'stringable multiplier' => ['mul', new StringableOperand('2.5'), '25.000000'],
            'stringable divisor' => ['div', new StringableOperand('2.5'), '4.000000'],
        ];
    }

    /**
     * @param mixed $divisor
     *
     * @dataProvider zeroDivisorProvider
     */
    public function testDivRejectsEveryZeroShape($divisor)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Division by zero');

        (new Money('10', 'EUR'))->div($divisor);
    }

    /**
     * @return array[]
     */
    public function zeroDivisorProvider()
    {
        return [
            'negative zero float' => [-0.0],
            'negative zero string' => ['-0'],
            'zero with decimals' => ['0.00'],
            'null' => [null],
            'false' => [false],
            'empty string' => [''],
            'integer zero' => [0],
            'float zero' => [0.0],
            'float literal that underflows to zero' => [1.0E-400],
            'negative zero with decimals' => ['-0.0'],
            'zero with a plus sign' => ['+0'],
            'zero without a leading digit' => ['.0'],
            'zero with a trailing dot' => ['0.'],
            'several zeros' => ['000'],
            'stringable zero' => [new StringableOperand('0')],
        ];
    }

    /**
     * @param mixed $operand
     *
     * @dataProvider invalidOperandProvider
     */
    public function testMulRejectsAnInvalidMultiplier($operand, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Invalid multiplier: ' . $message);

        (new Money('10', 'EUR'))->mul($operand);
    }

    /**
     * @param mixed $operand
     *
     * @dataProvider invalidOperandProvider
     */
    public function testDivRejectsAnInvalidDivisor($operand, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Invalid divisor: ' . $message);

        (new Money('10', 'EUR'))->div($operand);
    }

    /**
     * @return array[]
     */
    public function invalidOperandProvider()
    {
        return [
            'a trailing line feed' => ["2\n", '2\n'],
            'a decimal comma' => ['2,5', '2,5'],
            'an array' => [[2], 'array'],
            'not a number float' => [NAN, 'NAN'],
            'infinite float' => [INF, 'INF'],
            'zero in scientific notation' => ['0e0', '0e0'],
            'string in scientific notation' => ['1.0E-5', '1.0E-5'],
            'hexadecimal string' => ['0x1A', '0x1A'],
            'sign only' => ['+', '+'],
            'dot only' => ['.', '.'],
            'minus sign only' => ['-', '-'],
            'double sign' => ['--5', '--5'],
            'leading space' => [' 5', ' 5'],
            'trailing tab' => ["5\t", '5\t'],
            'negative infinite float' => [-INF, '-INF'],
            'stringable with a decimal comma' => [new StringableOperand('2,5'), '2,5'],
            'object without __toString' => [new stdClass(), 'object'],
        ];
    }

    /**
     * @dataProvider floatAndDecimalStringProvider
     */
    public function testMulGivesTheSameResultForAFloatAndItsDecimalString(float $float, string $decimal)
    {
        $money = new Money('1000000000000000000', 'EUR');

        $this->assertSame($money->mul($decimal)->getAmount(), $money->mul($float)->getAmount());
    }

    /**
     * @dataProvider floatAndDecimalStringProvider
     */
    public function testDivGivesTheSameResultForAFloatAndItsDecimalString(float $float, string $decimal)
    {
        $money = new Money('1000000000000000000', 'EUR');

        $this->assertSame($money->div($decimal)->getAmount(), $money->div($float)->getAmount());
    }

    /**
     * @return array[]
     */
    public function floatAndDecimalStringProvider()
    {
        return [
            'vat rate' => [0.21, '0.21'],
            'sum with binary noise' => [0.1 + 0.2, '0.3'],
            'thousandth' => [0.001, '0.001'],
            'thousand' => [1000.0, '1000'],
            'exact power of ten below 1e-4' => [1.0E-5, '0.00001'],
            'negative float below 1e-4' => [-0.000035, '-0.000035'],
            'negative power of ten below 1e-4' => [-1.0E-5, '-0.00001'],
            'very small float' => [1.0E-12, '0.000000000001'],
            'rate below 1e-4 with binary noise' => [0.0035 / 100, '0.000035'],
            'product below 1e-4 with binary noise' => [0.00001 * 3, '0.00003'],
            'smallest normal float' => [2.2250738585072014E-308, '0.' . str_repeat('0', 307) . '22250738585072'],
            'more than fourteen significant digits' => [123456789.123456789, '123456789.12346'],
            'float of at least 1e14' => [1.0E+15, '1000000000000000'],
            'power of ten not exact in binary' => [1.0E+23, '100000000000000000000000'],
        ];
    }

    /**
     * @dataProvider floatThatLosesDigitsProvider
     */
    public function testMulAndDivRejectAFloatInScientificNotationThatLosesDigits(
        string $method,
        float $operand,
        string $message
    ) {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage($message);

        (new Money('10', 'EUR'))->$method($operand);
    }

    /**
     * @return array[]
     */
    public function floatThatLosesDigitsProvider()
    {
        return [
            'multiplier with fifteen significant digits' => [
                'mul',
                123456789012345.0,
                'Invalid multiplier: 12345678901234',
            ],
            'divisor with fifteen significant digits' => ['div', 123456789012345.0, 'Invalid divisor: 12345678901234'],
            'negative multiplier with fifteen significant digits' => [
                'mul',
                -123456789012345.0,
                'Invalid multiplier: -12345678901234',
            ],
            'large multiplier with a fraction of digits' => [
                'mul',
                1.2345678901234567E+25,
                'Invalid multiplier: 1.23456789012345',
            ],
            'multiplier printed as 1.0E+14' => ['mul', 99999999999999.98, 'Invalid multiplier: 99999999999999.9'],
        ];
    }

    /**
     * @dataProvider precisionSettingProvider
     */
    public function testMulUsesTheDigitsOfThePrecisionSetting(string $precision, float $multiplier, string $expected)
    {
        if ($precision === '-1' && PHP_VERSION_ID < 70100) {
            $this->markTestSkipped('precision=-1 needs PHP 7.1 or later');
        }
        $previousPrecision = ini_get('precision');
        ini_set('precision', $precision);

        try {
            $this->assertSame($expected, (new Money('10', 'EUR'))->mul($multiplier)->getAmount());
        } finally {
            ini_set('precision', $previousPrecision);
        }
    }

    /**
     * @return array[]
     */
    public function precisionSettingProvider()
    {
        return [
            'fourteen digits' => ['14', 12345678901234.5, '123456789012340.000000'],
            'seventeen digits' => ['17', 12345678901234.5, '123456789012345.000000'],
            'shortest exact form' => ['-1', 12345678901234.5, '123456789012345.000000'],
            'seventeen digits, fifteen-digit float' => ['17', 123456789012345.0, '1234567890123450.000000'],
            'shortest exact form, fifteen-digit float' => ['-1', 123456789012345.0, '1234567890123450.000000'],
            'shortest exact form, binary noise' => ['-1', 0.1 + 0.2, '3.000000'],
            'just below 1e-4, printed as 0.0001' => ['14', 9.999999999999991E-5, '0.001000'],
            'fifteen digits, fifteen-digit fraction' => ['15', 271349031604523.34, '2713490316045230.000000'],
            'sixteen digits' => ['16', 12345678901234.5, '123456789012345.000000'],
            'one digit, below 1e-4' => ['1', 9.811861780384492E-5, '0.001000'],
            'sixty digits' => [
                '60',
                1.0E+60,
                '9999999999999999493871352970740188669636450110134100730839040.000000',
            ],
            'fifty-four digits' => [
                '54',
                9.999999999999999E+53,
                '9999999999999999081503569441270121106180565840020111360.000000',
            ],
        ];
    }

    /**
     * @dataProvider tinyFloatProvider
     */
    public function testMulByATinyFloatGivesZeroWithoutANotice(float $multiplier)
    {
        $this->assertSame('0.000000', (new Money('10', 'EUR'))->mul($multiplier)->getAmount());
    }

    /**
     * @dataProvider tinyFloatProvider
     */
    public function testDivByATinyNonZeroFloatIsNotADivisionByZero(float $divisor)
    {
        $this->assertTrue((new Money('1', 'EUR'))->div($divisor)->isPositive());
    }

    /**
     * @return array[]
     */
    public function tinyFloatProvider()
    {
        return [
            'below the sprintf precision limit' => [1.0E-40],
            'far below the sprintf precision limit' => [1.0E-60],
            'subnormal float' => [5.0E-324],
        ];
    }

    /**
     * @dataProvider numericLocaleProvider
     */
    public function testMulAndDivIgnoreTheNumericLocale(string $locale)
    {
        $previousLocale = setlocale(LC_NUMERIC, '0');
        if (setlocale(LC_NUMERIC, $locale) === false) {
            if (getenv('CI') !== false) {
                $this->fail(sprintf('Locale %s is not installed on CI', $locale));
            }
            $this->markTestSkipped(sprintf('Locale %s is not installed', $locale));
        }

        try {
            $money = new Money('10', 'EUR');

            $this->assertSame('15.000000', $money->mul(1.5)->getAmount());
            $this->assertSame('0.000100', $money->mul(1.0E-5)->getAmount());
            $this->assertSame('-0.000350', $money->mul(-0.000035)->getAmount());
            $this->assertSame('40.000000', $money->div(0.25)->getAmount());
            $this->assertSame('12.34', Money::createFromNoDelimiterAmount('1234', 'EUR', 2.5)->getAmount());
        } finally {
            setlocale(LC_NUMERIC, $previousLocale);
        }
    }

    /**
     * @return array[]
     */
    public function numericLocaleProvider()
    {
        return [
            'german' => ['de_DE.UTF-8'],
            'lithuanian' => ['lt_LT.UTF-8'],
        ];
    }

    /**
     * @param mixed $operand
     *
     * @dataProvider rejectedOperandProvider
     */
    public function testMulAndDivLeaveTheMoneyUnchangedWhenTheOperandIsRejected(string $method, $operand)
    {
        $money = new Money('10.50', 'EUR');
        $amountBefore = $money->getAmount();

        try {
            $money->$method($operand);
            $this->fail('MoneyException expected');
        } catch (MoneyException $exception) {
            $this->assertSame($amountBefore, $money->getAmount());
            $this->assertSame('EUR', $money->getCurrency());
        }
    }

    /**
     * @return array[]
     */
    public function rejectedOperandProvider()
    {
        return [
            'invalid multiplier' => ['mul', '2,5'],
            'infinite multiplier' => ['mul', INF],
            'invalid divisor' => ['div', '2,5'],
            'zero divisor' => ['div', '0'],
            'not a number divisor' => ['div', NAN],
            'empty string divisor' => ['div', ''],
            'null divisor' => ['div', null],
        ];
    }

    /**
     * @param int|string|float $fraction
     *
     * @dataProvider negativeFractionProvider
     */
    public function testCreateFromNoDelimiterAmountRejectsANegativeFraction($fraction, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage($message);

        Money::createFromNoDelimiterAmount('1234', 'EUR', $fraction);
    }

    /**
     * @return array[]
     */
    public function negativeFractionProvider()
    {
        return [
            'negative integer' => [-2, 'Fraction must not be negative: -2'],
            'negative string' => ['-1', 'Fraction must not be negative: -1'],
            'negative float' => [-1.0, 'Fraction must not be negative: -1'],
            'negative float beyond the integer range' => [-1.0E+19, 'Fraction must not be negative: -1.0E+19'],
        ];
    }

    /**
     * @param string|float $fraction
     *
     * @dataProvider outOfRangeFractionProvider
     */
    public function testCreateFromNoDelimiterAmountRejectsAFractionBeyondTheIntegerRange($fraction, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage($message);

        Money::createFromNoDelimiterAmount('1234', 'EUR', $fraction);
    }

    /**
     * @return array[]
     */
    public function outOfRangeFractionProvider()
    {
        return [
            'float far above the integer range' => [1.0E+19, 'Fraction is out of range: 1.0E+19'],
            'float that wraps to zero as an integer' => [
                1.8446744073709552E+19,
                'Fraction is out of range: 1.844674407371E+19',
            ],
            'float just at the integer limit' => [9.223372036854776E+18, 'Fraction is out of range: 9.2233720368548E+18'],
            'integer string beyond the integer range' => [
                '99999999999999999999',
                'Fraction is out of range: 99999999999999999999',
            ],
        ];
    }

    /**
     * @param mixed $fraction
     *
     * @dataProvider unusableFractionProvider
     */
    public function testCreateFromNoDelimiterAmountRejectsAFractionThatIsNotAFiniteNumber($fraction, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage($message);

        Money::createFromNoDelimiterAmount('5', 'EUR', $fraction);
    }

    /**
     * @return array[]
     */
    public function unusableFractionProvider()
    {
        return [
            'infinite float' => [INF, 'Fraction must be finite: INF'],
            'negative infinite float' => [-INF, 'Fraction must be finite: -INF'],
            'not a number float' => [NAN, 'Fraction must be finite: NAN'],
            'empty string' => ['', "Fraction must be a number: ''"],
            'hexadecimal string' => ['0x2', "Fraction must be a number: '0x2'"],
            'text' => ['two', "Fraction must be a number: 'two'"],
            'string that only starts with a number' => ['2abc', "Fraction must be a number: '2abc'"],
            'array' => [[2], 'Fraction must be a number: array'],
            'stringable object' => [new StringableOperand('2'), 'Fraction must be a number: object'],
        ];
    }

    /**
     * @param mixed $fraction
     *
     * @dataProvider validFractionProvider
     */
    public function testCreateFromNoDelimiterAmountReadsAFractionAsBefore(
        string $amount,
        string $currency,
        $fraction,
        string $expected
    ) {
        $this->assertSame($expected, Money::createFromNoDelimiterAmount($amount, $currency, $fraction)->getAmount());
    }

    /**
     * @return array[]
     */
    public function validFractionProvider()
    {
        return [
            'numeric string' => ['1234', 'EUR', '2', '12.34'],
            'integral float' => ['1234', 'EUR', 2.0, '12.34'],
            'fractional float' => ['1234', 'EUR', 2.5, '12.34'],
            'decimal string' => ['1234', 'EUR', '2.0', '12.34'],
            'string with a plus sign' => ['1234', 'EUR', '+2', '12.34'],
            'string with a leading space' => ['1234', 'EUR', ' 2', '12.34'],
            'string with a line feed' => ['1234', 'EUR', "2\n", '12.34'],
            'string in scientific notation' => ['1234', 'EUR', '2e0', '12.34'],
            'true' => ['1234', 'EUR', true, '123.4'],
            'zero as a string' => ['1234', 'JPY', '0', '1234'],
            'zero as a float' => ['1234', 'JPY', 0.0, '1234'],
            'negative zero string' => ['1234', 'JPY', '-0', '1234'],
            'negative zero float' => ['1234', 'JPY', -0.0, '1234'],
            'null' => ['1234', 'JPY', null, '1234'],
            'false' => ['1234', 'JPY', false, '1234'],
            'fraction between zero and one' => ['1234', 'EUR', 0.5, '1234'],
            'fraction between zero and one as a string' => ['1234', 'EUR', '0.5', '1234'],
            'fraction just below one' => ['1234', 'EUR', 0.9, '1234'],
            'string with a leading zero' => ['1234', 'EUR', '02', '12.34'],
            'fraction longer than the amount' => ['5', 'EUR', 3, '0.005'],
            'fraction above twenty' => ['1', 'EUR', 21, '0.000000000000000000001'],
            'empty amount' => ['', 'EUR', '2', '0.00'],
        ];
    }

    public function testCreateFromNoDelimiterAmountRejectsAnUnsupportedCurrencyWithAWholeNumberFraction()
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Unsupported currency: ZZZ');

        Money::createFromNoDelimiterAmount('1234', 'ZZZ', '2');
    }

    public function testMulByAStringableEmptyStringCountsAsZeroLikeAnEmptyString()
    {
        $money = new Money('10', 'EUR');

        $this->assertSame($money->mul('')->getAmount(), $money->mul(new StringableOperand(''))->getAmount());
    }

    public function testDivByAStringableEmptyStringIsADivisionByZeroLikeAnEmptyString()
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Division by zero');

        (new Money('10', 'EUR'))->div(new StringableOperand(''));
    }

    /**
     * @param mixed $operand
     *
     * @dataProvider blankOperandProvider
     */
    public function testMulAndDivRejectABlankOperandThatIsNotEmpty(string $method, $operand, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage($message);

        (new Money('10', 'EUR'))->$method($operand);
    }

    /**
     * @return array[]
     */
    public function blankOperandProvider()
    {
        return [
            'space multiplier' => ['mul', ' ', 'Invalid multiplier:  '],
            'space divisor' => ['div', ' ', 'Invalid divisor:  '],
            'line feed multiplier' => ['mul', "\n", 'Invalid multiplier: \n'],
            'null byte divisor' => ['div', "\0", 'Invalid divisor: \000'],
        ];
    }

    public function testMulByAnEmptyStringLeavesTheMoneyUnchanged()
    {
        $money = new Money('10.50', 'EUR');

        $result = $money->mul('');

        $this->assertSame('0.000000', $result->getAmount());
        $this->assertSame('10.50', $money->getAmount());
        $this->assertSame('EUR', $money->getCurrency());
    }

    public function testCreateFromNoDelimiterAmountWithAPlusSignCanBeNegated()
    {
        $this->assertSame('-0.05', Money::createFromNoDelimiterAmount('+5', 'EUR')->negate()->getAmount());
    }

    public function testCreateFromNoDelimiterAmountRejectsALineEnding()
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Invalid amount without delimiter: 1234\n');

        Money::createFromNoDelimiterAmount("1234\n", 'EUR');
    }

    public function testAnAmountWithTwoDecimalSeparatorsIsRejected()
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Invalid amount: 1.2.3');

        new Money('1.2.3', 'EUR');
    }

    /**
     * @param string|null $amount
     * @param string|int|null $expected
     *
     * @dataProvider amountGettersProvider
     */
    public function testAmountGetters(string $method, $amount, $expected)
    {
        $this->assertSame($expected, (new Money($amount, 'EUR'))->$method());
    }

    /**
     * @return array[]
     */
    public function amountGettersProvider()
    {
        return [
            'an empty amount means no amount' => ['getAmount', '', null],
            'amount in cents' => ['getAmountInCents', '10.50', 1050],
            'a fraction of a cent is dropped' => ['getAmountInCents', '0.019', 1],
            'negative amount in cents' => ['getAmountInCents', '-3', -300],
            'no amount has no cents' => ['getAmountInCents', null, null],
            'no amount has no minor units' => ['getAmountInMinorUnits', null, null],
        ];
    }

    /**
     * @dataProvider integerGetterProvider
     */
    public function testAmountsTooLargeForAnIntegerAreRejected(string $method)
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Amount is too large to be returned as integer');

        (new Money('100000000000000000000', 'EUR'))->$method();
    }

    /**
     * @return array[]
     */
    public function integerGetterProvider()
    {
        return [
            ['getAmountInCents'],
            ['getAmountInMinorUnits'],
        ];
    }

    /**
     * @param int|string $amount
     *
     * @dataProvider createFromCentsAndNoDelimiterAmountProvider
     */
    public function testCreateFromCentsAndNoDelimiterAmount(string $method, $amount, Money $expected)
    {
        $this->assertEquals($expected, Money::$method($amount, 'EUR'));
    }

    /**
     * @return array[]
     */
    public function createFromCentsAndNoDelimiterAmountProvider()
    {
        return [
            'cents' => ['createFromCents', 1050, new Money('10.500000', 'EUR')],
            'negative cents as a string' => ['createFromCents', '-5', new Money('-0.050000', 'EUR')],
            'negative amount without delimiter' => ['createFromNoDelimiterAmount', '-1234', new Money('-12.34', 'EUR')],
            'negative amount below one without delimiter' => [
                'createFromNoDelimiterAmount',
                '-5',
                new Money('-0.05', 'EUR'),
            ],
        ];
    }

    /**
     * @param string|null $amount
     * @param string|null $expected
     *
     * @dataProvider createFromAnEmptyValueProvider
     */
    public function testCreateFromAnEmptyValue(string $method, $amount, $expected)
    {
        $this->assertSame($expected, Money::$method($amount, 'EUR')->getAmount());
    }

    /**
     * @return array[]
     */
    public function createFromAnEmptyValueProvider()
    {
        return [
            'no cents' => ['createFromCents', null, null],
            'empty amount without delimiter' => ['createFromNoDelimiterAmount', '', '0.00'],
        ];
    }

    /**
     * @dataProvider createFromIntegerUnitsProvider
     */
    public function testCreateFromCentsOrMinorUnitsRejectsALineEnding(string $method)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Amount must be integer');

        Money::$method("1234\n", 'EUR');
    }

    /**
     * @dataProvider createFromIntegerUnitsProvider
     */
    public function testCreateFromCentsOrMinorUnitsRejectsANonInteger(string $method)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Amount must be integer');

        Money::$method('10.5', 'EUR');
    }

    /**
     * @return array[]
     */
    public function createFromIntegerUnitsProvider()
    {
        return [
            ['createFromCents'],
            ['createFromMinorUnits'],
        ];
    }

    public function testCheckReturnsTheSameMoneyWhenTheCurrencyHasEnoughDecimals()
    {
        $money = new Money('10.50', 'EUR');

        $this->assertSame($money, $money->check());
    }

    public function testCheckRejectsMoreDecimalsThanTheCurrencyHas()
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Too small fraction for the amount specified');

        (new Money('10.555', 'EUR'))->check();
    }

    public function testTheSameNonZeroAmountInAnotherCurrencyIsNotEqual()
    {
        $this->assertFalse((new Money('1', 'EUR'))->isEqual(new Money('1', 'USD')));
    }

    /**
     * @dataProvider isPositiveProvider
     */
    public function testIsPositive(string $amount, bool $expected)
    {
        $this->assertSame($expected, (new Money($amount, 'EUR'))->isPositive());
    }

    /**
     * @return array[]
     */
    public function isPositiveProvider()
    {
        return [
            ['0.01', true],
            ['0', false],
            ['-0.01', false],
        ];
    }

    /**
     * @param int|null $fraction
     * @param array<string, string> $expected
     *
     * @dataProvider getArrayRepresentationProvider
     */
    public function testGetArrayRepresentation(
        string $amount,
        string $currency,
        $fraction,
        string $separator,
        array $expected
    ) {
        $this->assertSame($expected, (new Money($amount, $currency))->getArrayRepresentation($fraction, $separator));
    }

    /**
     * @return array[]
     */
    public function getArrayRepresentationProvider()
    {
        return [
            'decimals of the currency' => [
                '10.5',
                'EUR',
                null,
                '.',
                ['amount' => '10.50', 'currency' => 'EUR'],
            ],
            'given fraction and separator' => [
                '10.5',
                'EUR',
                3,
                ',',
                ['amount' => '10,500', 'currency' => 'EUR'],
            ],
            'currency without decimals' => ['7', 'JPY', null, '.', ['amount' => '7', 'currency' => 'JPY']],
        ];
    }

    public function testGetFractionRejectsAnUnsupportedCurrency()
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Unsupported currency: ZZZ');

        Money::getFraction('zzz');
    }

    public function testSerializedFormIsStable()
    {
        $stored = 'O:25:"Evp\Component\Money\Money":2:{s:9:"' . "\0*\0" . 'amount";s:11:"1200.000000";'
            . 's:11:"' . "\0*\0" . 'currency";s:3:"EUR";}';

        $this->assertEquals(new Money('1200.000000', 'EUR'), unserialize($stored));
        $this->assertSame($stored, serialize(new Money('1200.000000', 'EUR')));
    }
}
