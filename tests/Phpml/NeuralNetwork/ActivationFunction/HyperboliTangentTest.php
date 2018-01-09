<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction\HyperbolicTangent;
use PHPUnit\Framework\TestCase;

class HyperboliTangentTest extends TestCase
{
    /**
     * @dataProvider tanhProvider
     */
    public function testHyperbolicTangentActivationFunction($beta, $expected, $value): void
    {
        $tanh = new HyperbolicTangent($beta);

        $this->assertEquals($expected, $tanh->compute($value), '', 0.001);
    }

    public function tanhProvider(): array
    {
        return [
            [1.0, 0.761, 1],
            [1.0, 0, 0],
            [1.0, 1, 4],
            [1.0, -1, -4],
            [0.5, 0.462, 1],
            [0.3, 0, 0],
        ];
    }

    /**
     * @dataProvider tanhDerivativeProvider
     */
    public function testHyperbolicTangentDerivative($beta, $expected, $value): void
    {
        $tanh = new HyperbolicTangent($beta);
        $activatedValue = $tanh->compute($value);
        $this->assertEquals($expected, $tanh->differentiate($value, $activatedValue), '', 0.001);
    }

    public function tanhDerivativeProvider(): array
    {
        return [
            [1.0, 0, -6],
            [1.0, 0.419, -1],
            [1.0, 1, 0],
            [1.0, 0.419, 1],
            [1.0, 0, 6],
            [0.5, 0.786, 1],
            [0.5, 0.786, -1],
            [0.3, 1, 0],
        ];
    }
}
