<?php

namespace Evp\Component\Money;

class AggregatedMoney
{
    /**
     * @var Money[]
     */
    protected $moneyList = array();

    /**
     * @param Money[] $moneyList
     *
     * @return $this
     */
    public function addAll(array $moneyList)
    {
        foreach ($moneyList as $money) {
            $this->add($money);
        }

        return $this;
    }

    /**
     * @param Money $money
     *
     * @return $this
     * @throws MoneyException
     */
    public function add(Money $money)
    {
        $currentMoney = $this->get($money->getCurrency());

        if ($currentMoney === null) {
            $currentMoney = $this->moneyList[$money->getCurrency()] = Money::createZero($money->getCurrency());
        }

        $this->moneyList[$money->getCurrency()] = $currentMoney->add($money);

        return $this;
    }

    /**
     * @param string $currency
     *
     * @return Money|null
     */
    public function get($currency)
    {
        return isset($this->moneyList[$currency]) ? $this->moneyList[$currency] : null;
    }

    /**
     * @return Money[]
     */
    public function getAll()
    {
        return array_values($this->moneyList);
    }
}
