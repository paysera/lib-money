<?php

declare(strict_types=1);

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\Money;
use Evp\Component\Money\Serializer;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    public function testMetadataDirectoryHoldsTheMoneyMapping()
    {
        $file = Serializer::getMetadataPath() . DIRECTORY_SEPARATOR . 'Money.xml';

        $this->assertFileExists($file);
        $this->assertXmlStringEqualsXmlString(
            '<serializer>'
            . '<class name="Evp\Component\Money\Money" access-type="public_method" exclusion-policy="ALL">'
            . '<property name="amount" type="string" expose="true"/>'
            . '<property name="currency" type="string" expose="true"/>'
            . '</class>'
            . '</serializer>',
            file_get_contents($file)
        );
    }

    public function testNamespacePrefixIsTheNamespaceOfMoney()
    {
        $this->assertSame('Evp\Component\Money', Serializer::getNamespacePrefix());
        $this->assertStringStartsWith(Serializer::getNamespacePrefix() . '\\', Money::class);
    }
}
