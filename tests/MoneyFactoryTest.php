<?php

declare(strict_types=1);

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\Money;
use Evp\Component\Money\MoneyException;
use Evp\Component\Money\MoneyFactory;
use Maba\Component\Math\BcMath;
use Maba\Component\Math\Exception\InvalidNumberException;
use Maba\Component\Math\Math;
use Maba\Component\Math\NumberValidator;
use Maba\Component\Monetary\Exception\InvalidAmountException;
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
     * @param array<int, mixed> $arguments
     *
     * @dataProvider createProvider
     */
    public function testCreate(string $method, array $arguments, Money $expected)
    {
        $result = $this->createFactory()->$method(...$arguments);

        $this->assertEquals($expected, $result);
        $this->assertSame($expected->getCurrency(), $result->getCurrency());
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
            'negative cents as a string' => ['createFromCents', ['-5', 'EUR'], new Money('-0.050000', 'EUR')],
            'cents as an integral float' => ['createFromCents', [1050.0, 'EUR'], new Money('10.500000', 'EUR')],
            'cents as a whole number with zero decimals' => ['createFromCents', ['1050.00', 'EUR'], new Money('10.500000', 'EUR')],
            'fractional cents, as before' => ['createFromCents', ['12.5', 'EUR'], new Money('0.125000', 'EUR')],
            'fractional negative float cents, as before' => ['createFromCents', [-0.5, 'EUR'], new Money('-0.005000', 'EUR')],
            'stringable fractional cents' => ['createFromCents', [new StringableOperand('12.5'), 'EUR'], new Money('0.125000', 'EUR')],
            'stringable amount' => ['create', [new StringableOperand('10.50'), 'EUR'], new Money('10.50', 'EUR')],
            'stringable currency' => ['create', ['10.50', new StringableOperand('eur')], new Money('10.50', 'EUR')],
            'stringable cents' => ['createFromCents', [new StringableOperand('500'), 'EUR'], new Money('5.000000', 'EUR')],
        ];
    }

    /**
     * @param mixed $amountInCents
     *
     * @dataProvider invalidCentsProvider
     */
    public function testCreateFromCentsRejectsANonNumber($amountInCents)
    {
        $this->expectException(InvalidNumberException::class);
        $this->expectExceptionMessage('Amount in cents must be a number');

        $this->createFactory()->createFromCents($amountInCents, 'EUR');
    }

    /**
     * @return array[]
     */
    public function invalidCentsProvider()
    {
        return [
            'a trailing line feed' => ["1234\n"],
            'an array' => [[1050]],
            'an object' => [new \stdClass()],
            'null' => [null],
            'a plus sign, rejected before as well' => ['+500'],
            'true' => [true],
            'false' => [false],
            'empty string' => [''],
            'stringable empty string' => [new StringableOperand('')],
            'stringable with a line feed' => [new StringableOperand("500\n")],
            'leading space' => [' 5'],
            'hexadecimal string' => ['0x1A'],
            'infinite float' => [INF],
            'not a number float' => [NAN],
            'integral float of 1e15' => [1.0E+15],
        ];
    }

    public function testCreateReportsAnInvalidAmountWithMoneyException()
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Amount has invalid chars: 12.34\\n');

        $this->createFactory()->create("12.34\n", 'EUR');
    }

    /**
     * @param mixed $amount
     *
     * @dataProvider invalidAmountProvider
     */
    public function testCreateRejectsAnInvalidAmount($amount, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage($message);

        $this->createFactory()->create($amount, 'EUR');
    }

    /**
     * @return array[]
     */
    public function invalidAmountProvider()
    {
        return [
            'an array' => [[1], 'Amount must be a number, array given'],
            'an object without __toString' => [new \stdClass(), 'Amount must be a number, object given'],
            'true' => [true, 'Amount must be a number, boolean given'],
            'false' => [false, 'Amount must be a number, boolean given'],
            'stringable with a line feed' => [new StringableOperand("12.34\n"), 'Amount has invalid chars: 12.34\n'],
            'stringable word' => [new StringableOperand('abc'), 'Amount has invalid chars: abc'],
            'stringable with two dots' => [new StringableOperand('1.2.3'), 'Invalid amount: 1.2.3'],
            'infinite float' => [INF, 'Amount has invalid chars: INF'],
            'not a number float' => [NAN, 'Amount has invalid chars: NAN'],
        ];
    }

    /**
     * @param mixed $currency
     *
     * @dataProvider unusableCurrencyProvider
     */
    public function testCreateRejectsAnUnusableCurrency($currency, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage($message);

        $this->createFactory()->create('1', $currency);
    }

    /**
     * @return array[]
     */
    public function unusableCurrencyProvider()
    {
        return [
            'an array' => [['EUR'], 'Currency must be a string, array given'],
            'an object' => [new \stdClass(), 'Currency must be a string, object given'],
            'empty string' => ['', 'Unsupported currency: '],
            'stringable empty string' => [new StringableOperand(''), 'Unsupported currency: '],
            'stringable unsupported code' => [new StringableOperand('zzz'), 'Unsupported currency: ZZZ'],
            'stringable code with a leading space' => [new StringableOperand(' EUR'), 'Unsupported currency:  EUR'],
            'stringable code with a line feed' => [new StringableOperand("EUR\n"), 'Unsupported currency: EUR\n'],
            'integer' => [978, 'Currency must be a string, integer given'],
            'float' => [9.78, 'Currency must be a string, double given'],
            'true' => [true, 'Currency must be a string, boolean given'],
        ];
    }

    public function testCreateReportsAnUnsupportedCurrencyWithMoneyException()
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Unsupported currency: ZZZ');

        $this->createFactory()->create('1', 'ZZZ');
    }

    public function testCreateValidatesTheCurrencyAgainstTheInformationProvider()
    {
        $this->expectException(InvalidCurrencyException::class);

        $this->createFactory(['USD'])->create('1', 'EUR');
    }

    public function testCreateUppercasesALowercaseStringableCurrencyBeforeTheInformationProvider()
    {
        $result = $this->createFactory(['EUR'])->create('1', new StringableOperand('eur'));

        $this->assertSame('EUR', $result->getCurrency());
    }

    public function testCreateValidatesAStringableCurrencyAgainstTheInformationProvider()
    {
        $this->expectException(InvalidCurrencyException::class);
        $this->expectExceptionMessage('Invalid currency specified: USD');

        $this->createFactory(['EUR'])->create('1', new StringableOperand('usd'));
    }

    public function testCreateRejectsAMissingCurrencyThroughTheValidator()
    {
        $this->expectException(InvalidCurrencyException::class);

        $this->createFactory()->create('1', null);
    }

    /**
     * @param mixed $amount
     *
     * @dataProvider emptyAmountProvider
     */
    public function testCreateRejectsAnEmptyAmountThroughTheValidator($amount)
    {
        $this->expectException(InvalidAmountException::class);
        $this->expectExceptionMessage('Invalid amount for money specified');

        $this->createFactory()->create($amount, 'EUR');
    }

    /**
     * @return array[]
     */
    public function emptyAmountProvider()
    {
        return [
            'null' => [null],
            'empty string' => [''],
            'stringable empty string' => [new StringableOperand('')],
        ];
    }

    public function testCreateReportsTheAmountBeforeTheCurrency()
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Amount must be a number, array given');

        $this->createFactory()->create([1], ['EUR']);
    }

    /**
     * @param array<int, mixed> $arguments
     *
     * @dataProvider boundaryCreateProvider
     */
    public function testCreateAtABoundary(string $method, array $arguments, string $amount, string $currency)
    {
        $result = $this->createFactory()->$method(...$arguments);

        $this->assertSame($amount, $result->getAmount());
        $this->assertSame($currency, $result->getCurrency());
    }

    /**
     * @return array[]
     */
    public function boundaryCreateProvider()
    {
        return [
            'integer amount' => ['create', [10, 'EUR'], '10', 'EUR'],
            'float amount' => ['create', [1.5, 'EUR'], '1.5', 'EUR'],
            'stringable zero currency' => ['createZero', [new StringableOperand('eur')], '0', 'EUR'],
            'cents with a stringable currency' => [
                'createFromCents',
                [5, new StringableOperand('eur')],
                '0.050000',
                'EUR',
            ],
            'zero cents' => ['createFromCents', [0, 'EUR'], '0.000000', 'EUR'],
            'negative zero cents' => ['createFromCents', ['-0', 'EUR'], '0.000000', 'EUR'],
            'cents with a leading zero' => ['createFromCents', ['05', 'EUR'], '0.050000', 'EUR'],
            'negative integral float cents' => ['createFromCents', [-1050.0, 'EUR'], '-10.500000', 'EUR'],
            'largest integer cents' => ['createFromCents', [PHP_INT_MAX, 'EUR'], '92233720368547758.070000', 'EUR'],
            'smallest integer cents' => ['createFromCents', [PHP_INT_MIN, 'EUR'], '-92233720368547758.080000', 'EUR'],
            'cents beyond the integer range' => [
                'createFromCents',
                ['99999999999999999999', 'EUR'],
                '999999999999999999.990000',
                'EUR',
            ],
        ];
    }

    public function testCreateFromCentsReportsTheCentsBeforeTheCurrency()
    {
        $this->expectException(InvalidNumberException::class);

        $this->createFactory()->createFromCents("1234\n", ['EUR']);
    }

    /**
     * @param mixed $currency
     *
     * @dataProvider centsCurrencyProvider
     */
    public function testCreateFromCentsRejectsAnUnusableCurrency($currency, string $message)
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage($message);

        $this->createFactory()->createFromCents(5, $currency);
    }

    /**
     * @return array[]
     */
    public function centsCurrencyProvider()
    {
        return [
            'stringable unsupported code' => [new StringableOperand('zzz'), 'Unsupported currency: ZZZ'],
            'empty string' => ['', 'Unsupported currency: '],
            'array' => [['EUR'], 'Currency must be a string, array given'],
        ];
    }

    public function testCreateFromCentsValidatesTheCurrencyAgainstTheInformationProvider()
    {
        $this->expectException(InvalidCurrencyException::class);
        $this->expectExceptionMessage('Invalid currency specified: EUR');

        $this->createFactory(['USD'])->createFromCents(5, 'EUR');
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
