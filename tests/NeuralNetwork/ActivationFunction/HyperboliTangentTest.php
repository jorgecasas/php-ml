<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction\HyperbolicTangent;
use PHPUnit\Framework\TestCase;

class HyperboliTangentTest extends TestCase
{
    /**
     * @dataProvider tanhProvider
     *
     * @param float|int $value
     */
    public function testHyperbolicTangentActivationFunction(float $beta, float $expected, $value): void
    {
        $tanh = new HyperbolicTangent($beta);

        self::assertEqualsWithDelta($expected, $tanh->compute($value), 0.001);
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
     *
     * @param float|int $value
     */
    public function testHyperbolicTangentDerivative(float $beta, float $expected, $value): void
    {
        $tanh = new HyperbolicTangent($beta);
        $activatedValue = $tanh->compute($value);
        self::assertEqualsWithDelta($expected, $tanh->differentiate($value, $activatedValue), 0.001);
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
