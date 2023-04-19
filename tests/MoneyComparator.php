<?php

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\Money;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;

class MoneyComparator extends Comparator
{

    public function accepts($expected, $actual)
    {
        return $expected instanceof Money && $actual instanceof Money;
    }

    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false)
    {
        assert($expected instanceof Money);
        assert($actual instanceof Money);

        if (!$expected->isEqual($actual)) {
            throw new ComparisonFailure(
                $expected,
                $actual,
                $expected->getAsString(),
                $actual->getAsString(),
                false,
                'Failed asserting that two Money objects are equal.'
            );
        }
    }
}
