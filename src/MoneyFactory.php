<?php

namespace Evp\Component\Money;

use Maba\Component\Math\Exception\InvalidNumberException;
use Maba\Component\Math\MathInterface as MabaMathInterface;
use Maba\Component\Monetary\Exception\InvalidAmountException;
use Maba\Component\Monetary\Exception\InvalidCurrencyException;
use Maba\Component\Monetary\Factory\MoneyFactoryInterface;
use Maba\Component\Monetary\Validation\MoneyValidator;

class MoneyFactory implements MoneyFactoryInterface
{
    protected $math;
    protected $validator;

    public function __construct(MabaMathInterface $math, MoneyValidator $validator)
    {
        $this->math = $math;
        $this->validator = $validator;
    }

    /**
     * @param string|int|float|object|null $amount
     * @param string|object|null $currency
     *
     * @return Money
     *
     * @throws MoneyException
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function create($amount, $currency)
    {
        $amount = self::castStringable($amount);
        $currency = self::castStringable($currency);
        if ($amount !== null && (!is_scalar($amount) || is_bool($amount))) {
            throw new MoneyException('Amount must be a number, ' . gettype($amount) . ' given');
        }
        if ($currency !== null && !is_string($currency)) {
            throw new MoneyException('Currency must be a string, ' . gettype($currency) . ' given');
        }
        $result = new Money($amount, $currency);
        $this->validator->validateMoney($result);
        return $result;
    }

    /**
     * Create zero Money
     *
     * @param string $currency
     *
     * @return Money
     *
     * @throws MoneyException
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function createZero($currency = null)
    {
        return $this->create('0', $currency);
    }

    /**
     * @param int|string|float|object $amountInCents
     * @param string $currency
     *
     * @return Money
     *
     * @throws InvalidNumberException
     * @throws MoneyException
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function createFromCents($amountInCents, $currency)
    {
        $amountInCents = self::castStringable($amountInCents);
        if (
            !is_scalar($amountInCents)
            || is_bool($amountInCents)
            || !preg_match(Money::DECIMAL_PATTERN, (string)$amountInCents)
        ) {
            throw new InvalidNumberException('Amount in cents must be a number');
        }

        return $this->create($this->math->div($amountInCents, '100'), $currency);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private static function castStringable($value)
    {
        return is_object($value) && method_exists($value, '__toString') ? (string)$value : $value;
    }
}
