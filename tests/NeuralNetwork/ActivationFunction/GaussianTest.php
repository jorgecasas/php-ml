<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction\Gaussian;
use PHPUnit\Framework\TestCase;

class GaussianTest extends TestCase
{
    /**
     * @dataProvider gaussianProvider
     *
     * @param float|int $value
     */
    public function testGaussianActivationFunction(float $expected, $value): void
    {
        $gaussian = new Gaussian();

        self::assertEqualsWithDelta($expected, $gaussian->compute($value), 0.001);
    }

    public function gaussianProvider(): array
    {
        return [
            [0.367, 1],
            [1, 0],
            [0.367, -1],
            [0, 3],
            [0, -3],
        ];
    }

    /**
     * @dataProvider gaussianDerivativeProvider
     *
     * @param float|int $value
     */
    public function testGaussianDerivative(float $expected, $value): void
    {
        $gaussian = new Gaussian();
        $activatedValue = $gaussian->compute($value);
        self::assertEqualsWithDelta($expected, $gaussian->differentiate($value, $activatedValue), 0.001);
    }

    public function gaussianDerivativeProvider(): array
    {
        return [
            [0, -5],
            [0.735, -1],
            [0.779, -0.5],
            [0, 0],
            [-0.779, 0.5],
            [-0.735, 1],
            [0, 5],
        ];
    }
}
