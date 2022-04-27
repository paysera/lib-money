<?php

namespace Evp\Component\Money;

class CurrencyIsoNumber
{
    /**
     * Currency ISO codes list
     *
     * @var array
     */
    static protected $codes = array(
        '971' => 'AFN',
        '978' => 'EUR',
        '008' => 'ALL',
        '012' => 'DZD',
        '840' => 'USD',
        '973' => 'AOA',
        '951' => 'XCD',
        '032' => 'ARS',
        '051' => 'AMD',
        '533' => 'AWG',
        '036' => 'AUD',
        '944' => 'AZN',
        '044' => 'BSD',
        '048' => 'BHD',
        '050' => 'BDT',
        '052' => 'BBD',
        '974' => 'BYR',
        '084' => 'BZD',
        '952' => 'XOF',
        '060' => 'BMD',
        '064' => 'BTN',
        '356' => 'INR',
        '068' => 'BOB',
        '984' => 'BOV',
        '977' => 'BAM',
        '072' => 'BWP',
        '578' => 'NOK',
        '986' => 'BRL',
        '096' => 'BND',
        '975' => 'BGN',
        '108' => 'BIF',
        '116' => 'KHR',
        '950' => 'XAF',
        '124' => 'CAD',
        '132' => 'CVE',
        '136' => 'KYD',
        '990' => 'CLF',
        '152' => 'CLP',
        '156' => 'CNY',
        '170' => 'COP',
        '970' => 'COU',
        '174' => 'KMF',
        '976' => 'CDF',
        '554' => 'NZD',
        '188' => 'CRC',
        '191' => 'HRK',
        '931' => 'CUC',
        '192' => 'CUP',
        '532' => 'ANG',
        '203' => 'CZK',
        '208' => 'DKK',
        '262' => 'DJF',
        '214' => 'DOP',
        '818' => 'EGP',
        '222' => 'SVC',
        '232' => 'ERN',
        '230' => 'ETB',
        '238' => 'FKP',
        '242' => 'FJD',
        '953' => 'XPF',
        '270' => 'GMD',
        '981' => 'GEL',
        '936' => 'GHS',
        '292' => 'GIP',
        '320' => 'GTQ',
        '826' => 'GBP',
        '324' => 'GNF',
        '328' => 'GYD',
        '332' => 'HTG',
        '340' => 'HNL',
        '344' => 'HKD',
        '348' => 'HUF',
        '352' => 'ISK',
        '360' => 'IDR',
        '960' => 'XDR',
        '364' => 'IRR',
        '368' => 'IQD',
        '376' => 'ILS',
        '388' => 'JMD',
        '392' => 'JPY',
        '400' => 'JOD',
        '398' => 'KZT',
        '404' => 'KES',
        '408' => 'KPW',
        '410' => 'KRW',
        '414' => 'KWD',
        '417' => 'KGS',
        '418' => 'LAK',
        '422' => 'LBP',
        '426' => 'LSL',
        '710' => 'ZAR',
        '430' => 'LRD',
        '434' => 'LYD',
        '756' => 'CHF',
        '446' => 'MOP',
        '807' => 'MKD',
        '969' => 'MGA',
        '454' => 'MWK',
        '458' => 'MYR',
        '462' => 'MVR',
        '478' => 'MRO',
        '480' => 'MUR',
        '965' => 'XUA',
        '484' => 'MXN',
        '979' => 'MXV',
        '498' => 'MDL',
        '496' => 'MNT',
        '504' => 'MAD',
        '943' => 'MZN',
        '104' => 'MMK',
        '516' => 'NAD',
        '524' => 'NPR',
        '558' => 'NIO',
        '566' => 'NGN',
        '512' => 'OMR',
        '586' => 'PKR',
        '590' => 'PAB',
        '598' => 'PGK',
        '600' => 'PYG',
        '604' => 'PEN',
        '608' => 'PHP',
        '985' => 'PLN',
        '634' => 'QAR',
        '946' => 'RON',
        '643' => 'RUB',
        '646' => 'RWF',
        '654' => 'SHP',
        '882' => 'WST',
        '678' => 'STD',
        '682' => 'SAR',
        '941' => 'RSD',
        '690' => 'SCR',
        '694' => 'SLL',
        '702' => 'SGD',
        '994' => 'XSU',
        '090' => 'SBD',
        '706' => 'SOS',
        '728' => 'SSP',
        '144' => 'LKR',
        '938' => 'SDG',
        '968' => 'SRD',
        '748' => 'SZL',
        '752' => 'SEK',
        '947' => 'CHE',
        '948' => 'CHW',
        '760' => 'SYP',
        '901' => 'TWD',
        '972' => 'TJS',
        '834' => 'TZS',
        '764' => 'THB',
        '776' => 'TOP',
        '780' => 'TTD',
        '788' => 'TND',
        '949' => 'TRY',
        '934' => 'TMT',
        '800' => 'UGX',
        '980' => 'UAH',
        '784' => 'AED',
        '997' => 'USN',
        '940' => 'UYI',
        '858' => 'UYU',
        '860' => 'UZS',
        '548' => 'VUV',
        '937' => 'VEF',
        '704' => 'VND',
        '886' => 'YER',
        '967' => 'ZMW',
        '932' => 'ZWL',
        '955' => 'XBA',
        '956' => 'XBB',
        '957' => 'XBC',
        '958' => 'XBD',
        '963' => 'XTS',
        '999' => 'XXX',
        '959' => 'XAU',
        '964' => 'XPD',
        '962' => 'XPT',
        '961' => 'XAG',
        '933' => 'BYN',
        '928' => 'VES',
        '929' => 'MRU',
    );

    /**
     * @param string $currencyNumber
     *
     * @return string
     *
     * @throws MoneyException
     */
    public function getCurrencyCode($currencyNumber)
    {
        if (!isset(self::$codes[$currencyNumber])) {
            throw new MoneyException(sprintf("Currency ISO number (%s) not exist", $currencyNumber));
        }
        return self::$codes[$currencyNumber];
    }

    /**
     * @param string $currencyCode
     *
     * @return string
     *
     * @throws MoneyException
     */
    public function getCurrencyNumber($currencyCode)
    {
        foreach (self::$codes as $currencyIsoNumber => $currencyIsoCode) {
            if ($currencyCode === $currencyIsoCode) {
                return $currencyIsoNumber;
            }
        }
        throw new MoneyException(sprintf("Currency ISO code (%s) not exist", $currencyCode));
    }
}
