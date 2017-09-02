<?php

declare(strict_types=1);

namespace tests\Phpml\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction\ThresholdedReLU;
use PHPUnit\Framework\TestCase;

class ThresholdedReLUTest extends TestCase
{
    /**
     * @param $theta
     * @param $expected
     * @param $value
     *
     * @dataProvider thresholdProvider
     */
    public function testThresholdedReLUActivationFunction($theta, $expected, $value)
    {
        $thresholdedReLU = new ThresholdedReLU($theta);

        $this->assertEquals($expected, $thresholdedReLU->compute($value));
    }

    /**
     * @return array
     */
    public function thresholdProvider()
    {
        return [
            [1.0, 0, 1.0],
            [0.5, 3.75, 3.75],
            [0.0, 0.5, 0.5],
            [0.9, 0, 0.1]
        ];
    }
}
