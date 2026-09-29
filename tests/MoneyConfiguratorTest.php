<?php

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\MoneyConfigurator;
use Evp\Component\Money\MoneyNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class MoneyConfiguratorTest extends TestCase
{
    public function testLoadRegistersTheMoneyNormalizer()
    {
        $container = new ContainerBuilder();

        (new MoneyConfigurator())->load($container);

        $this->assertEquals(
            ['evp_money.normalizer.money' => new Definition(MoneyNormalizer::class)],
            array_diff_key($container->getDefinitions(), (new ContainerBuilder())->getDefinitions())
        );
    }
}
