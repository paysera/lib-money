<?php

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\CurrencyIsoNumber;
use Evp\Component\Money\MoneyException;
use PHPUnit\Framework\TestCase;

class CurrencyIsoNumberTest extends TestCase
{
    /**
     * @param string $currencyNumber
     * @param string $expectedCurrencyNumber
     *
     * @dataProvider getCurrencyCodeProvider
     */
    public function testGetCurrencyCode($currencyNumber, $expectedCurrencyNumber)
    {
        $currencyIsoNumber = new CurrencyIsoNumber();
        $this->assertEquals($expectedCurrencyNumber, $currencyIsoNumber->getCurrencyCode($currencyNumber));
    }

    /**
     * @param string $currencyNumber
     *
     * @dataProvider getCurrencyCodeExceptionProvider
     */
    public function testGetCurrencyCodeException($currencyNumber)
    {
        $this->expectException(MoneyException::class);
        $currencyIsoNumber = new CurrencyIsoNumber();
        $currencyIsoNumber->getCurrencyCode($currencyNumber);
    }

    /**
     * @param string $currencyCode
     * @param string $expectedCurrencyCode
     *
     * @dataProvider getCurrencyNumberProvider
     */
    public function testGetCurrencyNumber($currencyCode, $expectedCurrencyCode)
    {
        $currencyIsoNumber = new CurrencyIsoNumber();
        $this->assertEquals($expectedCurrencyCode, $currencyIsoNumber->getCurrencyNumber($currencyCode));
    }

    /**
     * @param string $currencyCode
     *
     * @dataProvider getCurrencyNumberExceptionProvider
     */
    public function testGetCurrencyNumberException($currencyCode)
    {
        $this->expectException(MoneyException::class);
        $currencyIsoNumber = new CurrencyIsoNumber();
        $currencyIsoNumber->getCurrencyNumber($currencyCode);
    }

    /**
     * @return array
     */
    public function getCurrencyCodeProvider()
    {
        return [
            ['051', 'AMD'],
            ['032', 'ARS'],
            ['978', 'EUR'],
            ['826', 'GBP'],
        ];
    }

    /**
     * @return array
     */
    public function getCurrencyCodeExceptionProvider()
    {
        return [
            ['001'],
            ['099'],
            ['444'],
            ['741'],
        ];
    }

    /**
     * @return array
     */
    public function getCurrencyNumberProvider()
    {
        return [
            ['NOK', '578'],
            ['AUD', '036'],
            ['EUR', '978'],
            ['TRY', '949'],
        ];
    }

    /**
     * @return array
     */
    public function getCurrencyNumberExceptionProvider()
    {
        return [
            ['BUR'],
            ['ZIX'],
            ['MED'],
        ];
    }
}
