<?php

use Evp\Component\Money\Tests\MoneyComparator;
use SebastianBergmann\Comparator\Factory;

Factory::getInstance()->register(new MoneyComparator());
