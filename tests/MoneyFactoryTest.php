<?php

declare(strict_types=1);

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\Money;
use Evp\Component\Money\MoneyFactory;
use Maba\Component\Math\BcMath;
use Maba\Component\Math\Math;
use Maba\Component\Math\NumberValidator;
use Maba\Component\Monetary\Exception\InvalidCurrencyException;
use Maba\Component\Monetary\Factory\MoneyFactoryInterface;
use Maba\Component\Monetary\Information\MoneyInformationProvider;
use Maba\Component\Monetary\Validation\MoneyValidator;
use PHPUnit\Framework\TestCase;

class MoneyFactoryTest extends TestCase
{
    public function testImplementsTheMonetaryFactoryInterface()
    {
        $this->assertInstanceOf(MoneyFactoryInterface::class, $this->createFactory());
    }

    /**
     * @param array<int, string|int> $arguments
     *
     * @dataProvider createProvider
     */
    public function testCreate(string $method, array $arguments, Money $expected)
    {
        $this->assertEquals($expected, $this->createFactory()->$method(...$arguments));
    }

    /**
     * @return array[]
     */
    public function createProvider()
    {
        return [
            'amount' => ['create', ['10.50', 'EUR'], new Money('10.50', 'EUR')],
            'zero' => ['createZero', ['EUR'], new Money('0', 'EUR')],
            'cents' => ['createFromCents', [1050, 'EUR'], new Money('10.500000', 'EUR')],
        ];
    }

    public function testCreateValidatesTheCurrencyAgainstTheInformationProvider()
    {
        $this->expectException(InvalidCurrencyException::class);

        $this->createFactory(['USD'])->create('1', 'EUR');
    }

    /**
     * @param string[]|null $availableCurrencies
     *
     * @return MoneyFactory
     */
    private function createFactory($availableCurrencies = null)
    {
        $numberValidator = new NumberValidator();
        $math = new Math(new BcMath(6, $numberValidator));

        return new MoneyFactory(
            $math,
            new MoneyValidator($math, new MoneyInformationProvider(null, $availableCurrencies), $numberValidator)
        );
    }
}
