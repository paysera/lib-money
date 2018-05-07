<?php

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\AggregatedMoney;
use Evp\Component\Money\Money;

class AggregatedMoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param Money $one
     * @param Money $two
     * @param Money $expectedAmount
     *
     * @dataProvider addProvider
     */
    public function testAdd(Money $one, Money $two, Money $expectedAmount)
    {
        $aggregateMoney = new AggregatedMoney();

        $aggregateMoney
            ->add($one)
            ->add($two);

        $this->assertEquals($aggregateMoney->get($expectedAmount->getCurrency()), $expectedAmount);
    }

    public function addProvider()
    {
        return array(
            array(new Money('1', 'EUR'), new Money('2', 'EUR'), new Money('3', 'EUR')),
            array(new Money('-10', 'EUR'), new Money('2', 'EUR'), new Money('-8', 'EUR')),
            array(new Money('-10', 'EUR'), new Money('-10', 'EUR'), new Money('-20', 'EUR')),
            array(new Money('1', 'EUR'), new Money('-2', 'EUR'), new Money('-1', 'EUR'))
        );
    }
}