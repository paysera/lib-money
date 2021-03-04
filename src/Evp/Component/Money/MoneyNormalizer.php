<?php

namespace Evp\Component\Money;

use Paysera\Component\Serializer\Normalizer\DenormalizerInterface;
use Paysera\Component\Serializer\Normalizer\NormalizerInterface;
use Paysera\Component\Serializer\Exception\InvalidDataException;

class MoneyNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * Maps raw data to some structure. Usually array to entity object
     *
     * @param array $data
     *
     * @return Money
     *
     * @throws InvalidDataException
     */
    public function mapToEntity($data)
    {
        if (!isset($data['amount'])) {
            throw new InvalidDataException('Amount is not set');
        }

        if (!isset($data['currency'])) {
            throw new InvalidDataException('Currency is not set');
        }

        return $this->mapFromAmount($data['amount'], $data['currency']);
    }

    /**
     * @param string|float $amount
     * @param string       $currency
     *
     * @return Money
     * @throws InvalidDataException
     */
    public function mapFromAmount($amount, $currency)
    {
        try {
            $money = Money::create($amount, $currency);
        } catch (\InvalidArgumentException $exception) {
            throw new InvalidDataException('Invalid amount specified');
        }
        if (!$money->round()->isEqual($money)) {
            throw new InvalidDataException('Too small fraction for the amount specified');
        }
        return $money;
    }

    /**
     * @param integer $amountInCents
     * @param string  $currency
     *
     * @return Money
     * @deprecated 2.5.0 use MoneyNormalizer::mapFromMinorUnits instead
     * @throws InvalidDataException
     */
    public function mapFromCents($amountInCents, $currency)
    {
        try {
            $money = Money::createFromCents($amountInCents, $currency);
        } catch (\InvalidArgumentException $exception) {
            throw new InvalidDataException('Invalid amount specified');
        }
        if (!$money->round()->isEqual($money)) {
            throw new InvalidDataException('Too small fraction for the amount specified');
        }
        return $money;
    }

    /**
     * @param integer $amountInMinorUnits
     * @param string  $currency
     *
     * @return Money
     * @throws InvalidDataException
     * @throws \Exception
     */
    public function mapFromMinorUnits($amountInMinorUnits, $currency)
    {
        try {
            $money = Money::createFromMinorUnits($amountInMinorUnits, $currency);
        } catch (\InvalidArgumentException $exception) {
            throw new InvalidDataException('Invalid amount specified');
        }
        if (!$money->round()->isEqual($money)) {
            throw new InvalidDataException('Too small fraction for the amount specified');
        }
        return $money;
    }

    /**
     * Maps some structure to raw data. Usually entity object to array
     *
     * @param Money $entity
     *
     * @return array
     *
     * @throws InvalidDataException
     */
    public function mapFromEntity($entity)
    {
        if (!$entity instanceof Money) {
            throw new InvalidDataException('Provided argument is not a Money object.');
        }

        return array(
            'amount'   => $entity->formatAmount(),
            'currency' => $entity->getCurrency(),
        );
    }
}
