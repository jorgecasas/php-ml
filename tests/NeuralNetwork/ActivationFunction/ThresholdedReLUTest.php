<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction\ThresholdedReLU;
use PHPUnit\Framework\TestCase;

class ThresholdedReLUTest extends TestCase
{
    /**
     * @dataProvider thresholdProvider
     *
     * @param float|int $value
     */
    public function testThresholdedReLUActivationFunction(float $theta, float $expected, $value): void
    {
        $thresholdedReLU = new ThresholdedReLU($theta);

        self::assertEquals($expected, $thresholdedReLU->compute($value));
    }

    public function thresholdProvider(): array
    {
        return [
            [1.0, 0, 1.0],
            [0.5, 3.75, 3.75],
            [0.0, 0.5, 0.5],
            [0.9, 0, 0.1],
        ];
    }

    /**
     * @dataProvider thresholdDerivativeProvider
     *
     * @param float|int $value
     */
    public function testThresholdedReLUDerivative(float $theta, float $expected, $value): void
    {
        $thresholdedReLU = new ThresholdedReLU($theta);
        $activatedValue = $thresholdedReLU->compute($value);
        self::assertEquals($expected, $thresholdedReLU->differentiate($value, $activatedValue));
    }

    public function thresholdDerivativeProvider(): array
    {
        return [
            [0, 1, 1],
            [0, 1, 0],
            [0.5, 1, 1],
            [0.5, 1, 1],
            [0.5, 0, 0],
            [2, 0, -1],
        ];
    }
}
