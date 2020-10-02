<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction\BinaryStep;
use PHPUnit\Framework\TestCase;

class BinaryStepTest extends TestCase
{
    /**
     * @dataProvider binaryStepProvider
     *
     * @param float|int $value
     */
    public function testBinaryStepActivationFunction(float $expected, $value): void
    {
        $binaryStep = new BinaryStep();

        self::assertEquals($expected, $binaryStep->compute($value));
    }

    public function binaryStepProvider(): array
    {
        return [
            [1, 1],
            [1, 0],
            [0, -0.1],
        ];
    }

    /**
     * @dataProvider binaryStepDerivativeProvider
     *
     * @param float|int $value
     */
    public function testBinaryStepDerivative(float $expected, $value): void
    {
        $binaryStep = new BinaryStep();
        $activatedValue = $binaryStep->compute($value);
        self::assertEquals($expected, $binaryStep->differentiate($value, $activatedValue));
    }

    public function binaryStepDerivativeProvider(): array
    {
        return [
            [0, -1],
            [1, 0],
            [0, 1],
        ];
    }
}
