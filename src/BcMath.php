<?php

namespace Evp\Component\Money;

/**
 * @deprecated
 * @see evp/lib-math
 */
class BcMath implements MathInterface
{
    /**
     * @var int
     */
    private $scale;

    /**
     * Class constructor
     *
     * @param int $scale
     */
    public function __construct($scale)
    {
        $this->setScale($scale);
    }

    /**
     * Add
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return string
     */
    public function add($leftOperand, $rightOperand)
    {
        return bcadd($leftOperand, $rightOperand, $this->scale);
    }

    /**
     * Subtract
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return string
     */
    public function sub($leftOperand, $rightOperand)
    {
        return bcsub($leftOperand, $rightOperand, $this->scale);
    }

    /**
     * Divide
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return string
     *
     * @throws \Exception
     */
    public function div($leftOperand, $rightOperand)
    {
        if (empty($rightOperand) || $rightOperand == '0') {
            throw new \Exception('Right operand is empty/zero!');
        }
        return $this->round(bcdiv($leftOperand, $rightOperand, $this->scale + 1), $this->scale);
    }

    /**
     * Multiply
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return string
     */
    public function mul($leftOperand, $rightOperand)
    {
        return $this->round(bcmul($leftOperand, $rightOperand, $this->scale + 1), $this->scale);
    }

    /**
     * Compare
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return int Returns:
     *                -1 (if leftOperand < rightOperand)
     *                 0 (if leftOperand = rightOperand)
     *                 1 (if leftOperand > rightOperand)
     */
    public function comp($leftOperand, $rightOperand)
    {
        return bccomp($leftOperand, $rightOperand, $this->scale);
    }

    /**
     * Round
     *
     * @param string $operand
     * @param int $precision
     *
     * @return string
     */
    public function round($operand, $precision = 0)
    {
        return ($this->comp($operand, 0) >= 0)
            ? bcadd($operand, '0.' . str_repeat('0', $precision) . '5', $precision)
            : bcsub($operand, '0.' . str_repeat('0', $precision) . '5', $precision);
    }

    /**
     * Ceil
     *
     * @param string $operand
     * @param null|int $precision
     *
     * @return string
     */
    public function ceil($operand, $precision = 0)
    {
        $flooredOperand = $this->floor($operand, $precision);

        if ($this->comp($operand, 0) == 1) {
            $add = '0';

            if ($this->comp($operand, $flooredOperand) == 1) {
                $add = ($precision > 0) ? '0.' . str_repeat('0', $precision - 1) . '1' : '1';
            }

            $operand = bcadd($operand, $add, $precision);
        } else {
            $operand = bcsub($operand, 0, $precision);
        }

        return $operand;
    }

    /**
     * Floor
     *
     * @param string $operand
     * @param null|int $precision
     *
     * @return string
     */
    public function floor($operand, $precision = 0)
    {
        $multiplier = '1' . str_repeat('0', $precision);
        $operand = bcmul($operand, $multiplier, $this->scale);
        $parts = explode('.', $operand);
        $operand = $parts[0];

        if ($this->comp($operand, 0) < 0 && isset($parts[1]) && $this->comp($parts[1], 0) > 0) {
            $operand = bcsub($operand, 1, 0);
        }

        return bcdiv($operand, $multiplier, $precision);
    }

    /**
     * Set scale
     *
     * @param int $scale
     *
     * @return BcMath
     */
    public function setScale($scale)
    {
        $this->scale = $scale;
        return $this;
    }

    /**
     * Get scale
     *
     * @return int
     */
    public function getScale()
    {
        return $this->scale;
    }
}
