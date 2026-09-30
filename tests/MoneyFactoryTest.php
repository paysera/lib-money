<?php

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
     * @param string $method
     * @param array<int, string|int> $arguments
     * @param array<string, string> $expected
     *
     * @dataProvider createProvider
     */
    public function testCreate($method, array $arguments, array $expected)
    {
        $money = $this->createFactory()->$method(...$arguments);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertSame($expected, ['amount' => $money->getAmount(), 'currency' => $money->getCurrency()]);
    }

    /**
     * @return array[]
     */
    public function createProvider()
    {
        return [
            'amount' => ['create', ['10.50', 'EUR'], ['amount' => '10.50', 'currency' => 'EUR']],
            'zero' => ['createZero', ['EUR'], ['amount' => '0', 'currency' => 'EUR']],
            'cents' => [
                'createFromCents',
                [1050, 'EUR'],
                ['amount' => '10.500000', 'currency' => 'EUR'],
            ],
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
