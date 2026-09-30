<?php

declare(strict_types=1);

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\Money;
use Evp\Component\Money\Serializer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class SerializerTest extends TestCase
{
    public function testMetadataDirectoryHoldsTheMoneyMapping()
    {
        $file = Serializer::getMetadataPath() . DIRECTORY_SEPARATOR . 'Money.xml';

        $this->assertFileExists($file);
        $this->assertXmlStringEqualsXmlString(
            '<serializer>'
            . '<class name="' . Money::class . '" access-type="public_method" exclusion-policy="ALL">'
            . '<property name="amount" type="string" expose="true"/>'
            . '<property name="currency" type="string" expose="true"/>'
            . '</class>'
            . '</serializer>',
            file_get_contents($file)
        );
    }

    public function testNamespacePrefixIsTheNamespaceOfMoney()
    {
        $this->assertSame((new ReflectionClass(Money::class))->getNamespaceName(), Serializer::getNamespacePrefix());
    }
}
