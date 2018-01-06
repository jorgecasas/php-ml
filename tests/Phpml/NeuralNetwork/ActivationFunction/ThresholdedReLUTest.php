<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction\ThresholdedReLU;
use PHPUnit\Framework\TestCase;

class ThresholdedReLUTest extends TestCase
{
    /**
     * @dataProvider thresholdProvider
     */
    public function testThresholdedReLUActivationFunction($theta, $expected, $value): void
    {
        $thresholdedReLU = new ThresholdedReLU($theta);

        $this->assertEquals($expected, $thresholdedReLU->compute($value));
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
}
