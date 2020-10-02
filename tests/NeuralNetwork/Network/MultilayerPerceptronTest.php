<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\Network;

use Phpml\Exception\InvalidArgumentException;
use Phpml\NeuralNetwork\ActivationFunction;
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Network\MultilayerPerceptron;
use Phpml\NeuralNetwork\Node\Neuron;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MultilayerPerceptronTest extends TestCase
{
    public function testThrowExceptionWhenHiddenLayersAreEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Provide at least 1 hidden layer');

        $this->getMockForAbstractClass(
            MultilayerPerceptron::class,
            [5, [], [0, 1], 1000, null, 0.42]
        );
    }

    public function testThrowExceptionWhenThereIsOnlyOneClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Provide at least 2 different classes');

        $this->getMockForAbstractClass(
            MultilayerPerceptron::class,
            [5, [3], [0], 1000, null, 0.42]
        );
    }

    public function testThrowExceptionWhenClassesAreNotUnique(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Classes must be unique');

        $this->getMockForAbstractClass(
            MultilayerPerceptron::class,
            [5, [3], [0, 1, 2, 3, 1], 1000, null, 0.42]
        );
    }

    public function testLearningRateSetter(): void
    {
        /** @var MultilayerPerceptron $mlp */
        $mlp = $this->getMockForAbstractClass(
            MultilayerPerceptron::class,
            [5, [3], [0, 1], 1000, null, 0.42]
        );

        self::assertEquals(0.42, $mlp->getLearningRate());
        self::assertEquals(0.42, $mlp->getBackpropagation()->getLearningRate());

        $mlp->setLearningRate(0.24);
        self::assertEquals(0.24, $mlp->getLearningRate());
        self::assertEquals(0.24, $mlp->getBackpropagation()->getLearningRate());
    }

    public function testLearningRateSetterWithCustomActivationFunctions(): void
    {
        $activation_function = $this->getActivationFunctionMock();

        /** @var MultilayerPerceptron $mlp */
        $mlp = $this->getMockForAbstractClass(
            MultilayerPerceptron::class,
            [5, [[3, $activation_function], [5, $activation_function]], [0, 1], 1000, null, 0.42]
        );

        self::assertEquals(0.42, $mlp->getLearningRate());
        self::assertEquals(0.42, $mlp->getBackpropagation()->getLearningRate());

        $mlp->setLearningRate(0.24);
        self::assertEquals(0.24, $mlp->getLearningRate());
        self::assertEquals(0.24, $mlp->getBackpropagation()->getLearningRate());
    }

    public function testLearningRateSetterWithLayerObject(): void
    {
        $activation_function = $this->getActivationFunctionMock();

        /** @var MultilayerPerceptron $mlp */
        $mlp = $this->getMockForAbstractClass(
            MultilayerPerceptron::class,
            [5, [new Layer(3, Neuron::class, $activation_function), new Layer(5, Neuron::class, $activation_function)], [0, 1], 1000, null, 0.42]
        );

        self::assertEquals(0.42, $mlp->getLearningRate());
        self::assertEquals(0.42, $mlp->getBackpropagation()->getLearningRate());

        $mlp->setLearningRate(0.24);
        self::assertEquals(0.24, $mlp->getLearningRate());
        self::assertEquals(0.24, $mlp->getBackpropagation()->getLearningRate());
    }

    /**
     * @return ActivationFunction|MockObject
     */
    private function getActivationFunctionMock()
    {
        return $this->getMockForAbstractClass(ActivationFunction::class);
    }
}
