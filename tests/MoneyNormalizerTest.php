<?php

declare(strict_types=1);

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\Money;
use Evp\Component\Money\MoneyNormalizer;
use Paysera\Component\Serializer\Exception\InvalidDataException;
use Paysera\Component\Serializer\Normalizer\DenormalizerInterface;
use Paysera\Component\Serializer\Normalizer\NormalizerInterface;
use PHPUnit\Framework\TestCase;

class MoneyNormalizerTest extends TestCase
{
    public function testImplementsBothPayseraSerializerInterfaces()
    {
        $normalizer = new MoneyNormalizer();

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
        $this->assertInstanceOf(DenormalizerInterface::class, $normalizer);
    }

    /**
     * @param array<string, string|int> $data
     *
     * @dataProvider mapToEntityProvider
     */
    public function testMapToEntity(array $data, Money $expected)
    {
        $this->assertEquals($expected, (new MoneyNormalizer())->mapToEntity($data));
    }

    /**
     * @return array[]
     */
    public function mapToEntityProvider()
    {
        return [
            'decimal point' => [['amount' => '10.50', 'currency' => 'EUR'], new Money('10.50', 'EUR')],
            'decimal comma and lower-case currency' => [
                ['amount' => '10,50', 'currency' => 'eur'],
                new Money('10.50', 'EUR'),
            ],
            'integer amount' => [['amount' => 7, 'currency' => 'JPY'], new Money('7', 'JPY')],
            'negative amount' => [['amount' => '-0.01', 'currency' => 'USD'], new Money('-0.01', 'USD')],
        ];
    }

    /**
     * @param array<string, string> $data
     *
     * @dataProvider mapToEntityRejectsProvider
     */
    public function testMapToEntityRejects(array $data, string $message)
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage($message);

        (new MoneyNormalizer())->mapToEntity($data);
    }

    /**
     * @return array[]
     */
    public function mapToEntityRejectsProvider()
    {
        return [
            'no amount' => [['currency' => 'EUR'], 'Amount is not set'],
            'no currency' => [['amount' => '1'], 'Currency is not set'],
            'not a number' => [['amount' => '1.2.3', 'currency' => 'EUR'], 'Invalid amount specified'],
            'unknown currency, reported as an invalid amount' => [
                ['amount' => '1', 'currency' => 'ZZZ'],
                'Invalid amount specified',
            ],
            'more decimals than the currency has' => [
                ['amount' => '10.555', 'currency' => 'EUR'],
                'Too small fraction for the amount specified',
            ],
        ];
    }

    /**
     * @dataProvider mapFromCentsAndMinorUnitsProvider
     */
    public function testMapFromCentsAndMinorUnits(string $method, int $amount, string $currency, Money $expected)
    {
        $this->assertEquals($expected, (new MoneyNormalizer())->$method($amount, $currency));
    }

    /**
     * @return array[]
     */
    public function mapFromCentsAndMinorUnitsProvider()
    {
        return [
            'cents' => ['mapFromCents', 1050, 'EUR', new Money('10.500000', 'EUR')],
            'minor units of a currency with two decimals' => [
                'mapFromMinorUnits',
                1050,
                'EUR',
                new Money('10.500000', 'EUR'),
            ],
            'minor units of a currency without decimals' => [
                'mapFromMinorUnits',
                1050,
                'JPY',
                new Money('1050.000000', 'JPY'),
            ],
            'minor units of a currency with three decimals' => [
                'mapFromMinorUnits',
                1050,
                'KWD',
                new Money('1.050000', 'KWD'),
            ],
        ];
    }

    /**
     * @param mixed $amount
     *
     * @dataProvider mapFromCentsAndMinorUnitsRejectsProvider
     */
    public function testMapFromCentsAndMinorUnitsRejects(string $method, $amount, string $currency, string $message)
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage($message);

        (new MoneyNormalizer())->$method($amount, $currency);
    }

    /**
     * @return array[]
     */
    public function mapFromCentsAndMinorUnitsRejectsProvider()
    {
        return [
            'cents that are not an integer' => ['mapFromCents', '10.5', 'EUR', 'Invalid amount specified'],
            'cents of a currency without decimals' => [
                'mapFromCents',
                1,
                'JPY',
                'Too small fraction for the amount specified',
            ],
            'minor units that are not an integer' => [
                'mapFromMinorUnits',
                '10.5',
                'EUR',
                'Invalid amount specified',
            ],
        ];
    }

    /**
     * @param array<string, string> $expected
     *
     * @dataProvider mapFromEntityProvider
     */
    public function testMapFromEntity(string $amount, string $currency, array $expected)
    {
        $this->assertSame($expected, (new MoneyNormalizer())->mapFromEntity(new Money($amount, $currency)));
    }

    /**
     * @return array[]
     */
    public function mapFromEntityProvider()
    {
        return [
            'padded to the decimals of the currency' => [
                '10.5',
                'EUR',
                ['amount' => '10.50', 'currency' => 'EUR'],
            ],
            'truncated, not rounded, to the two decimals of EUR' => [
                '10.559',
                'EUR',
                ['amount' => '10.55', 'currency' => 'EUR'],
            ],
            'currency without decimals' => ['1050', 'JPY', ['amount' => '1050', 'currency' => 'JPY']],
        ];
    }

    public function testMapFromEntityRejectsAnythingButMoney()
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('Provided argument is not a Money object.');

        (new MoneyNormalizer())->mapFromEntity(['amount' => '1', 'currency' => 'EUR']);
    }

    public function testRoundTrip()
    {
        $normalizer = new MoneyNormalizer();
        $money = new Money('1234.56', 'EUR');

        $this->assertEquals($money, $normalizer->mapToEntity($normalizer->mapFromEntity($money)));
    }
}
