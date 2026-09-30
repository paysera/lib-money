<?php

namespace Evp\Component\Money\Tests;

use Evp\Component\Money\BcMath;
use Exception;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class BcMathTest extends TestCase
{
    /**
     * @var \Evp\Component\Money\BcMath
     */
    private $math;

    /**
     * Set up
     */
    public function set_up()
    {
        $this->math = new BcMath($scale = 6);
    }

    /**
     * Test add
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $result
     *
     * @dataProvider addProvider
     */
    public function testAdd($leftOperand, $rightOperand, $result)
    {
        $this->assertEquals(0, $this->math->comp($this->math->add($leftOperand, $rightOperand), $result));
    }

    /**
     * Test sub
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $result
     *
     * @dataProvider subProvider
     */
    public function testSub($leftOperand, $rightOperand, $result)
    {
        $this->assertEquals(0, $this->math->comp($this->math->sub($leftOperand, $rightOperand), $result));
    }

    /**
     * Test mul
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $result
     *
     * @dataProvider mulProvider
     */
    public function testMul($leftOperand, $rightOperand, $result)
    {
        $this->assertEquals(0, $this->math->comp($this->math->mul($leftOperand, $rightOperand), $result));
    }

    /**
     * Test div
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $result
     *
     * @dataProvider divProvider
     */
    public function testDiv($leftOperand, $rightOperand, $result)
    {
        $this->assertEquals(0, $this->math->comp($this->math->div($leftOperand, $rightOperand), $result));
    }

    /**
     * Test ceil
     *
     * @param string $operand
     * @param int $precision
     * @param string $result
     *
     * @dataProvider ceilProvider
     */
    public function testCeil($operand, $precision, $result)
    {
        $this->assertEquals(0, $this->math->comp($result, $this->math->ceil($operand, $precision)));
    }

    /**
     * Test floor
     *
     * @param string $operand
     * @param int $precision
     * @param string $result
     *
     * @dataProvider floorProvider
     */
    public function testFloor($operand, $precision, $result)
    {
        $this->assertEquals(0, $this->math->comp($result, $this->math->floor($operand, $precision)));
    }

    /**
     * Test round
     *
     * @param string $operand
     * @param int $precision
     * @param string $result
     *
     * @dataProvider roundProvider
     */
    public function testRound($operand, $precision, $result)
    {
        $this->assertEquals(0, $this->math->comp($result, $this->math->round($operand, $precision)));
    }

    /**
     * Add provider
     *
     * @return array
     */
    public function addProvider()
    {
        return [
            ['1', '1', '2'],
            ['-1', '-1', '-2'],
            ['1', '-1', '0'],
            ['-1', '1', '0'],
            ['0.5', '0.5', '1'],
            ['-0.5', '-0.5', '-1'],
            ['-0.5', '0', '-0.5'],
            ['-0', '0', '0'],
        ];
    }

    /**
     * Sub provider
     *
     * @return array
     */
    public function subProvider()
    {
        return [
            ['1', '1', '0'],
            ['-1', '-1', '0'],
            ['1', '-1', '2'],
            ['-1', '1', '-2'],
            ['0.5', '0.5', '0'],
            ['-0.5', '-0.5', '0'],
            ['-0.5', '0', '-0.5'],
            ['-0', '0', '0'],
        ];
    }

    /**
     * Mul provider
     *
     * @return array
     */
    public function mulProvider()
    {
        return [
            ['1', '1', '1'],
            ['-1', '-1', '1'],
            ['1', '-1', '-1'],
            ['-1', '1', '-1'],
            ['0.5', '0.5', '0.25'],
            ['-0.5', '-0.5', '0.25'],
            ['-0.5', '0', '0'],
            ['-0', '0', '0'],
        ];
    }

    /**
     * Div provider
     *
     * @return array
     */
    public function divProvider()
    {
        return [
            ['1', '1', '1'],
            ['1', '2', '0.5'],
            ['1', '3', '0.333333'],
            ['-1', '-1', '1'],
            ['1', '-1', '-1'],
            ['-1', '1', '-1'],
            ['0.5', '0.5', '1'],
            ['-0.5', '-0.5', '1'],
            ['35', '3.5', '10'],
        ];
    }

    /**
     * Ceil provider
     *
     * @return array
     */
    public function ceilProvider()
    {
        return [
            ['10.5481', 3, '10.549'],
            ['35', 2, '35'],
            ['-10.548', 2, '-10.54'],
            ['10.444444', 0, '11'],
            ['10.999999', 0, '11'],
            ['10.0000', 0, '10'],
            ['10.0001', 0, '11'],
            ['10.000001', 0, '11'],
            ['10.0000001', 0, '10'], // Exceeds default scale = 6
            ['0', 0, '0'],
            ['0', 8, '0'],
        ];
    }

    /**
     * Floor provider
     *
     * @return array
     */
    public function floorProvider()
    {
        return [
            ['10.5489', 3, '10.548'],
            ['35', 2, '35'],
            ['-10.548', 2, '-10.55'],
            ['10.444444', 0, '10'],
            ['10.999999', 0, '10'],
            ['10.0000', 0, '10'],
            ['10.0009', 0, '10'],
            ['10.000009', 0, '10'],
            ['10.0000001', 0, '10'],
            ['-10.548', 3, '-10.548'],
            ['10.548', 3, '10.548'],
            ['-10548', 0, '-10548'],
            ['10548', 0, '10548'],
            ['0', 0, '0'],
            ['0', 5, '0'],
            ['0.00', 3, '0'],
        ];
    }

    /**
     * Round provider
     *
     * @return array
     */
    public function roundProvider()
    {
        return [
            ['10.544', 2, '10.54'],
            ['-10.544', 2, '-10.54'],
            ['10.545', 2, '10.55'],
            ['10.444444', 0, '10'],
            ['10.488888', 0, '10'],
            ['10.588888', 0, '11'],
            ['10.000000', 6, '10.000000'],
            ['10.0000555', 6, '10.000056'],
            ['10.000055565', 8, '10.00005557'],
            ['0', 0, '0'],
            ['0', 8, '0'],
        ];
    }

    /**
     * @param mixed $rightOperand
     *
     * @dataProvider zeroDivisorProvider
     */
    public function testDivisionByZeroIsRejected($rightOperand)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Right operand is empty/zero!');

        $this->math->div('1', $rightOperand);
    }

    /**
     * @return array[]
     */
    public function zeroDivisorProvider()
    {
        return [
            ['0'],
            [''],
            [0],
            ['0.00'],
            ['-0'],
        ];
    }

    public function testScale()
    {
        $this->assertSame(6, $this->math->getScale());
        $this->assertSame(2, $this->math->setScale(2)->getScale());
    }
}
