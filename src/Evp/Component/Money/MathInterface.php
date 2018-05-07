<?php

namespace Evp\Component\Money;

/**
 * @deprecated
 * @see evp/lib-math
 */
interface MathInterface
{
    /**
     * Add
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return string
     */
    public function add($leftOperand, $rightOperand);

    /**
     * Subtract
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return string
     */
    public function sub($leftOperand, $rightOperand);

    /**
     * Divide
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return string
     */
    public function div($leftOperand, $rightOperand);

    /**
     * Multiply
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return string
     */
    public function mul($leftOperand, $rightOperand);

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
    public function comp($leftOperand, $rightOperand);

    /**
     * Round
     *
     * @param string $operand
     * @param int $precision
     *
     * @return string
     */
    public function round($operand, $precision = 0);

    /**
     * Ceil
     *
     * @param string $operand
     * @param int $precision
     *
     * @return string
     */
    public function ceil($operand, $precision = 0);

    /**
     * Floor
     *
     * @param string $operand
     * @param int $precision
     *
     * @return string
     */
    public function floor($operand, $precision = 0);
}
