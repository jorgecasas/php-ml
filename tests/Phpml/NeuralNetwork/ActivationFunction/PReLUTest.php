<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\ActivationFunction;

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

    public function preluProvider(): array
    {
        return [
            [0.01, 0.367, 0.367],
            [0.0, 1, 1],
            [0.3, -0.3, -1],
            [0.9, 3, 3],
            [0.02, -0.06, -3],
        ];
    }

    /**
     * @dataProvider preluDerivativeProvider
     */
    public function testPReLUDerivative($beta, $expected, $value): void
    {
        $prelu = new PReLU($beta);
        $activatedValue = $prelu->compute($value);
        $this->assertEquals($expected, $prelu->differentiate($value, $activatedValue));
    }

    public function preluDerivativeProvider(): array
    {
        return [
            [0.5, 0.5, -3],
            [0.5, 1, 0],
            [0.5, 1, 1],
            [0.01, 1, 1],
            [1, 1, 1],
            [0.3, 1, 0.1],
            [0.1, 0.1, -0.1],
        ];
    }
}
