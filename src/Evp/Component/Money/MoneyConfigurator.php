<?php

namespace Evp\Component\Money;

use Evp\Component\DependencyInjection\ConfiguratorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class MoneyConfigurator implements ConfiguratorInterface
{

    public function load(ContainerBuilder $container)
    {
        $container->setDefinition(
            'evp_money.normalizer.money',
            new Definition('Evp\Component\Money\MoneyNormalizer')
        );
    }

} 
