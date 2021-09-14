<?php

namespace Evp\Component\Money;

use Maba\Component\Monetary\MoneyInterface;

class Money implements MoneyInterface
{
    const DEFAULT_SCALE = 6;

    /**
     * Currency fractions
     *
     * @var array
     */
    static protected $fractions = array(
        'AED' => 2,
        'AFN' => 2,
        'ALL' => 2,
        'AMD' => 0,
        'ANG' => 2,
        'AOA' => 1,
        'ARS' => 2,
        'AUD' => 2,
        'AWG' => 2,
        'AZN' => 2,
        'BAM' => 2,
        'BBD' => 2,
        'BDT' => 2,
        'BGN' => 2,
        'BHD' => 3,
        'BIF' => 0,
        'BMD' => 2,
        'BND' => 2,
        'BOB' => 2,
        'BRL' => 2,
        'BSD' => 2,
        'BTN' => 2,
        'BWP' => 2,
        'BYR' => 0,
        'BYN' => 2,
        'BZD' => 2,
        'CAD' => 2,
        'CDF' => 2,
        'CHF' => 2,
        'CLP' => 0,
        'CNY' => 2,
        'COP' => 2,
        'CRC' => 2,
        'CUP' => 2,
        'CVE' => 2,
        'CZK' => 2,
        'DJF' => 0,
        'DKK' => 2,
        'DOP' => 2,
        'DZD' => 2,
        'EEK' => 2,
        'EGP' => 2,
        'ERN' => 2,
        'ETB' => 2,
        'EUR' => 2,
        'FJD' => 2,
        'FKP' => 2,
        'GBP' => 2,
        'GEL' => 2,
        'GHS' => 2,
        'GIP' => 2,
        'GMD' => 2,
        'GNF' => 0,
        'GTQ' => 2,
        'GYD' => 2,
        'HKD' => 2,
        'HNL' => 2,
        'HRK' => 2,
        'HTG' => 2,
        'HUF' => 2,
        'IDR' => 0,
        'ILS' => 2,
        'INR' => 2,
        'IQD' => 0,
        'IRR' => 0,
        'ISK' => 0,
        'JMD' => 2,
        'JOD' => 3,
        'JPY' => 0,
        'KES' => 2,
        'KGS' => 2,
        'KHR' => 0,
        'KMF' => 0,
        'KPW' => 0,
        'KRW' => 0,
        'KWD' => 3,
        'KYD' => 2,
        'KZT' => 2,
        'LAK' => 0,
        'LBP' => 2,
        'LKR' => 2,
        'LRD' => 2,
        'LSL' => 2,
        'LTL' => 2,
        'LVL' => 2,
        'LYD' => 3,
        'MAD' => 2,
        'MDL' => 2,
        'MGA' => 1,
        'MKD' => 2,
        'MMK' => 0,
        'MNT' => 2,
        'MOP' => 1,
        'MRO' => 1,
        'MRU' => 2,
        'MUR' => 2,
        'MVR' => 2,
        'MWK' => 2,
        'MXN' => 2,
        'MYR' => 2,
        'MZN' => 2,
        'NAD' => 2,
        'NGN' => 2,
        'NIO' => 2,
        'NOK' => 2,
        'NPR' => 2,
        'NZD' => 2,
        'OMR' => 3,
        'PAB' => 2,
        'PEN' => 2,
        'PGK' => 2,
        'PHP' => 2,
        'PKR' => 2,
        'PLN' => 2,
        'PYG' => 0,
        'QAR' => 2,
        'RON' => 2,
        'RSD' => 2,
        'RUB' => 2,
        'RUR' => 2,
        'RWF' => 0,
        'SAR' => 2,
        'SBD' => 2,
        'SCR' => 2,
        'SDG' => 2,
        'SEK' => 2,
        'SGD' => 2,
        'SHP' => 2,
        'SLL' => 0,
        'SOS' => 2,
        'SRD' => 2,
        'STD' => 0,
        'SYP' => 2,
        'SZL' => 2,
        'THB' => 2,
        'TJS' => 2,
        'TMT' => 2,
        'TND' => 3,
        'TOP' => 2,
        'TRY' => 2,
        'TTD' => 2,
        'TWD' => 1,
        'TZS' => 2,
        'UAH' => 2,
        'UGX' => 0,
        'USD' => 2,
        'UYU' => 2,
        'UZS' => 2,
        'VEF' => 2,
        'VES' => 2,
        'VND' => 0,
        'VUV' => 0,
        'WST' => 2,
        'XAF' => 0,
        'XCD' => 2,
        'XOF' => 0,
        'XPF' => 0,
        'YER' => 0,
        'ZAR' => 2,
        'ZMW' => 0,
        'ZWL' => 2,

        'XAU' => 5,
        'XAG' => 4,
        'XPT' => 6,
    );

    /**
     * @var string
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * Class construct
     *
     * @param string|int $amount
     * @param string $currency
     */
    public function __construct($amount = null, $currency = null)
    {
        if ($amount !== null) {
            $this->setAmount($amount);
        }
        if ($currency !== null) {
            $this->setCurrency($currency);
        }
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return static
     *
     * @throws MoneyException
     */
    public function setAmount($amount)
    {
        if ($amount === '' || $amount === null) {
            $this->amount = null;
        } else {
            $amount = str_replace(',', '.', $amount);
            if (substr_count($amount, '.') > 1) {
                throw new MoneyException('Invalid amount: ' . $amount);
            }

            if (!preg_match('/^[-+]?\d+(\.\d+)?$/', $amount)) {
                throw new MoneyException('Amount has invalid chars: ' . $amount);
            }

            $amount = preg_replace('/^([-+]?)0+(\d)/', '$1$2', $amount);

            $this->amount = $amount != ''
                ? $amount
                : null;
        }

        return $this;
    }

    /**
     * Sets amount in cents
     *
     * @param integer $amountInCents
     *
     * @return static
     *
     * @throws MoneyException
     */
    protected function setAmountInCents($amountInCents)
    {
        if (!preg_match('/^-?\d+$/', $amountInCents)) {
            throw new MoneyException('Amount must be integer');
        }

        $amount = $this->getMath()->div((string)$amountInCents, '100');
        $this->setAmount($amount);
        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return integer|null
     *
     * @throws \RuntimeException
     */
    public function getAmountInCents()
    {
        if ($this->amount === null) {
            return null;
        }

        $result = $this->getMath()->floor($this->getMath()->mul($this->amount, '100'));
        if ((string)intval($result) !== $result) {
            throw new \RuntimeException('Amount is too large to be returned as integer');
        }

        return intval($result);
    }

    /**
     * @param integer|null $amountInMinorUnits
     * @param string|null $currency
     * @return $this
     * @throws \Exception
     */
    protected function setAmountInMinorUnits($amountInMinorUnits = null, $currency = null)
    {
        if (!preg_match('/^-?\d+$/', $amountInMinorUnits)) {
            throw new MoneyException('Amount must be integer');
        }
        $fraction = static::getFraction($currency);
        $amount = $this->getMath()->div((string)$amountInMinorUnits, pow(10, $fraction));
        $this->setAmount($amount);
        return $this;
    }

    /**
     * @return integer|null
     *
     * @throws \RuntimeException
     */
    public function getAmountInMinorUnits()
    {
        if ($this->amount === null) {
            return null;
        }

        $fraction = static::getFraction($this->getCurrency());

        $result = $this->getMath()->floor($this->getMath()->mul($this->amount, pow(10, $fraction)));
        if ((string)intval($result) !== $result) {
            throw new \RuntimeException('Amount is too large to be returned as integer');
        }

        return intval($result);
    }

    /**
     * Format money amount
     *
     * @param integer $fraction
     * @param string $decimalSeperator
     * @param string $thousandsSeperator
     *
     * @return string
     *
     * @throws MoneyException
     */
    public function formatAmount($fraction = null, $decimalSeperator = '.', $thousandsSeperator = null)
    {
        if ($fraction === null) {
            $fraction = static::getFraction($this->getCurrency());
        }

        if ($fraction <= 0) {
            return number_format($this->amount, $fraction, $decimalSeperator, $thousandsSeperator ? $thousandsSeperator : '');
        }
        $pos = strpos($this->amount, '.');
        if ($pos === false) {
            if ($this->amount == 0) {
                return '0' . $decimalSeperator . str_repeat('0', $fraction);
            } else {
                if ($thousandsSeperator) {
                    return number_format($this->amount, $fraction, $decimalSeperator, $thousandsSeperator);
                } else {
                    return $this->amount . $decimalSeperator . str_repeat('0', $fraction);
                }
            }
        } else {
            if ($thousandsSeperator) {
                return number_format(substr($this->amount, 0, $pos + 1 + $fraction), $fraction, $decimalSeperator, $thousandsSeperator);
            } else {
                $formatted = str_replace('.', $decimalSeperator, substr($this->amount, 0, $pos + 1 + $fraction));
                $formatted = str_pad($formatted, $pos + mb_strlen($decimalSeperator) + $fraction, '0');

                return $formatted;
            }
        }
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return $this
     *
     * @throws MoneyException
     */
    public function setCurrency($currency)
    {
        $currency = strtoupper($currency);

        if (!isset(static::$fractions[$currency])) {
            throw new MoneyException(sprintf('Unsupported currency: %s', $currency));
        }

        $this->currency = $currency;
        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Add money
     *
     * @param Money $money
     *
     * @return static
     *
     * @throws MoneyException
     */
    public function add(Money $money)
    {
        if (!$this->isSameCurrency($money)) {
            throw new MoneyException(sprintf(
                "Operand currency doesn't match (lop_currency=%s, rop_currency=%s)",
                $this->getCurrency(),
                $money->getCurrency()
            ));
        }

        return new static($this->getMath()->add($this->getAmount(), $money->getAmount()), $this->getCurrency());
    }

    /**
     * Sub money
     *
     * @param Money $money
     *
     * @return static
     *
     * @throws MoneyException
     */
    public function sub(Money $money)
    {
        if (!$this->isSameCurrency($money)) {
            throw new MoneyException(sprintf(
                "Operand currency doesn't match (lop_currency=%s, rop_currency=%s)",
                $this->getCurrency(),
                $money->getCurrency()
            ));
        }

        return new static($this->getMath()->sub($this->getAmount(), $money->getAmount()), $this->getCurrency());
    }

    /**
     * Mul money
     *
     * @param string|int $multiplier
     *
     * @return static
     */
    public function mul($multiplier)
    {
        return new static($this->getMath()->mul($this->getAmount(), $multiplier), $this->getCurrency());
    }

    /**
     * Div money
     *
     * @param string|int $divisor
     *
     * @return static
     *
     * @throws MoneyException
     */
    public function div($divisor)
    {
        if ($divisor == '0') {
            throw new MoneyException('Division by zero');
        }
        return new static($this->getMath()->div($this->getAmount(), $divisor), $this->getCurrency());
    }

    /**
     * Negates money
     *
     * @return static
     */
    public function negate()
    {
        if ($this->isZero()) {
            $amount = $this->getAmount();
        } else if ($this->isNegative()) {
            $amount = substr($this->getAmount(), 1);
        } else {
            $amount = '-' . $this->getAmount();
        }

        return new static($amount, $this->getCurrency());
    }

    /**
     * Rounds Money
     *
     * @param integer $precision
     *
     * @return static
     */
    public function round($precision = null)
    {
        if ($precision === null) {
            $precision = static::getFraction($this->getCurrency());
        }

        return new static($this->getMath()->round($this->getAmount(), $precision), $this->getCurrency());
    }

    /**
     * Ceils Money
     *
     * @param integer $precision
     *
     * @return static
     */
    public function ceil($precision = null)
    {
        if ($precision === null) {
            $precision = static::getFraction($this->getCurrency());
        }

        return new static($this->getMath()->ceil($this->getAmount(), $precision), $this->getCurrency());
    }

    /**
     * Floors Money
     *
     * @param integer $precision
     *
     * @return static
     */
    public function floor($precision = null)
    {
        if ($precision === null) {
            $precision = static::getFraction($this->getCurrency());
        }

        return new static($this->getMath()->floor($this->getAmount(), $precision), $this->getCurrency());
    }

    /**
     * Checks if current amount does not violate default precision. Throws exception if violates, returns self if not
     *
     * @throws MoneyException
     * @return Money
     */
    public function check()
    {
        if (!$this->round()->isEqual($this)) {
            throw new MoneyException('Too small fraction for the amount specified');
        }
        return $this;
    }

    /**
     * Is greater
     *
     * @param Money $money
     *
     * @return boolean
     *
     * @throws MoneyException
     */
    public function isGt(Money $money)
    {
        if (!$this->isSameCurrency($money)) {
            throw new MoneyException(sprintf(
                "Operand currency doesn't match (lop_currency=%s, rop_currency=%s)",
                $this->getCurrency(),
                $money->getCurrency()
            ));
        }

        return $this->getMath()->comp($this->getAmount(), $money->getAmount()) == 1;
    }

    /**
     * Is greater or equal
     *
     * @param Money $money
     *
     * @return bool
     *
     * @throws MoneyException
     */
    public function isGte(Money $money)
    {
        if (!$this->isSameCurrency($money)) {
            throw new MoneyException(sprintf(
                "Operand currency doesn't match (lop_currency=%s, rop_currency=%s)",
                $this->getCurrency(),
                $money->getCurrency()
            ));
        }

        return $this->getMath()->comp($this->getAmount(), $money->getAmount()) >= 0;
    }

    /**
     * Is less
     *
     * @param Money $money
     *
     * @return boolean
     *
     * @throws MoneyException
     */
    public function isLt(Money $money)
    {
        if (!$this->isSameCurrency($money)) {
            throw new MoneyException(sprintf(
                "Operand currency doesn't match (lop_currency=%s, rop_currency=%s)",
                $this->getCurrency(),
                $money->getCurrency()
            ));
        }

        return $this->getMath()->comp($this->getAmount(), $money->getAmount()) == -1;
    }

    /**
     * Is less or equal
     *
     * @param Money $money
     *
     * @return boolean
     *
     * @throws MoneyException
     */
    public function isLte(Money $money)
    {
        if (!$this->isSameCurrency($money)) {
            throw new MoneyException(sprintf(
                "Operand currency doesn't match (lop_currency=%s, rop_currency=%s)",
                $this->getCurrency(),
                $money->getCurrency()
            ));
        }

        return $this->getMath()->comp($this->getAmount(), $money->getAmount()) <= 0;
    }

    /**
     * Is equal
     *
     * @param Money $money
     *
     * @return boolean
     *
     * @throws MoneyException
     */
    public function isEqual(Money $money)
    {
        if ($this->isZero() && $money->isZero()) {
            return true;
        }

        if (!$this->isSameCurrency($money)) {
            return false;
        }

        return $this->getMath()->comp($this->getAmount(), $money->getAmount()) == 0;
    }

    /**
     * Checks if money is negative
     *
     * @return boolean
     */
    public function isNegative()
    {
        return $this->getMath()->comp($this->getAmount(), '0') == -1;
    }

    /**
     * Checks if money is zero
     *
     * @return boolean
     */
    public function isZero()
    {
        return $this->getMath()->comp($this->getAmount(), '0') == 0;
    }

    /**
     * Checks if money is positive
     *
     * @return boolean
     */
    public function isPositive()
    {
        return $this->getMath()->comp($this->getAmount(), '0') == 1;
    }

    /**
     * Is same currency
     *
     * @param Money $money
     *
     * @return boolean
     */
    public function isSameCurrency(Money $money)
    {
        return $this->getCurrency() == $money->getCurrency();
    }

    /**
     * Absolute value
     *
     * @return Money
     */
    public function abs()
    {
        $amount = $this->getAmount();
        if ($this->getMath()->comp($this->getAmount(), 0) < 0) {
            $amount = $this->getMath()->mul($this->getAmount(), '-1');
        }

        return new static($amount, $this->getCurrency());
    }

    /**
     * Converts Money to array
     *
     * @param int|null $fraction
     * @param string $separator
     *
     * @return array
     */
    public function getArrayRepresentation($fraction = null, $separator = '.')
    {
        return array(
            'amount' => (string)$this->formatAmount($fraction, $separator),
            'currency' => $this->getCurrency(),
        );
    }

    /**
     * @deprecated
     * @see Money::getAsString
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->getAsString();
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * @return string
     *
     * @throws MoneyException
     */
    public function getAsString()
    {
        return $this->formatAmount() . ' ' . $this->getCurrency();
    }

    /**
     * Get fraction (decimal places)
     *
     * @param string $currency
     *
     * @return int
     *
     * @throws MoneyException
     */
    static public function getFraction($currency)
    {
        $currency = strtoupper($currency);
        if (!isset(static::$fractions[$currency])) {
            throw new MoneyException(sprintf('Unsupported currency: %s', $currency));
        }
        return static::$fractions[$currency];
    }

    /**
     * Get Money object from amount with no delimiter
     *
     * @param string $amount
     * @param string $currency
     * @param integer $fraction
     *
     * @return static
     */
    static public function createFromNoDelimiterAmount($amount, $currency, $fraction = 2)
    {
        $amount = (string)$amount;
        if ($amount === '') {
            $amount = '0';
        }
        if ($amount[0] === '-') {
            $minus = true;
            $amount = substr($amount, 1);
        } else {
            $minus = false;
        }
        $amount = str_repeat('0', $fraction) . $amount;
        $amountArray = str_split($amount);
        $left = array_slice($amountArray, 0, -$fraction);
        $right = array_slice($amountArray, -$fraction);
        $left = ltrim(implode('', $left), '0');
        $left = $left === '' ? '0' : $left;
        $amount = $left . '.' . implode('', $right);
        if ($minus) {
            $amount = '-' . $amount;
        }
        return new static($amount, $currency);
    }

    /**
     * Create zero Money
     *
     * @param string $currency
     *
     * @return static
     */
    static public function createZero($currency)
    {
        return new static('0', $currency);
    }

    /**
     * Creates object instance. Used for fluent interface
     *
     * @param string|int|null $amount
     * @param string|null $currency
     *
     * @return static
     */
    static public function create($amount = null, $currency = null)
    {
        return new static($amount, $currency);
    }

    /**
     * Creates object instance. Used for fluent interface
     *
     * @param string|int|null $amountInCents
     * @param string|null $currency
     * @deprecated 2.5.0 use Money::createFromMinorUnits instead
     * @return static
     */
    static public function createFromCents($amountInCents = null, $currency = null)
    {
        $money = new static();
        if ($amountInCents !== null) {
            $money->setAmountInCents($amountInCents);
        }
        if ($currency !== null) {
            $money->setCurrency($currency);
        }
        return $money;
    }

    /**
     * Creates object instance. Used for fluent interface
     *
     * @param string|int|null $amountInMinorUnits
     * @param string|null $currency
     *
     * @return static
     * @throws \Exception
     */
    static public function createFromMinorUnits($amountInMinorUnits = null, $currency = null)
    {
        $money = new static();
        if ($amountInMinorUnits !== null) {
            $money->setAmountInMinorUnits($amountInMinorUnits, $currency);
        }
        if ($currency !== null) {
            $money->setCurrency($currency);
        }
        return $money;
    }

    /**
     * Clear multiple commas and dots
     *
     * @param string $amount
     *
     * @return string
     */
    static public function clearAmountValue($amount)
    {
        $array = explode('.', str_replace(' ', '', trim(str_replace([',', ' '], ['.', ''], $amount))));

        if (count($array) > 1) {
            $end = '.' . array_pop($array);
        } else {
            $end = '';
        }

        if ($end === '.') {
            $end = '';
        }

        if (
            isset($array[0])
            && strlen($array[0]) === 0
        ) {
            $array[0] = '0';
        }

        return (implode('', $array) . $end);
    }

    /**
     * Get BcMath instance
     *
     * @return MathInterface
     */
    private static function getMath()
    {
        static $instance;

        if ($instance === null) {
            $instance = new BcMath(static::DEFAULT_SCALE);
        }

        return $instance;
    }
}
