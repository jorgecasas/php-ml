<?php

declare(strict_types=1);

namespace tests\Phpml\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction\PReLU;
use PHPUnit\Framework\TestCase;

class PReLUTest extends TestCase
{
    /**
     * @dataProvider preluProvider
     */
    public function testPReLUActivationFunction($beta, $expected, $value): void
    {
        $prelu = new PReLU($beta);

        $this->assertEquals($expected, $prelu->compute($value), '', 0.001);
    }

    /**
     * @return array
     */
    public function preluProvider()
    {
        return [
            [0.01, 0.367, 0.367],
            [0.0, 1, 1],
            [0.3, -0.3, -1],
            [0.9, 3, 3],
            [0.02, -0.06, -3],
        ];
    }
}
