<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\Network;

use Phpml\NeuralNetwork\ActivationFunction;
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Network\MultilayerPerceptron;
use Phpml\NeuralNetwork\Node\Neuron;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class MultilayerPerceptronTest extends TestCase
{
    public function testLearningRateSetter(): void
    {
        /** @var MultilayerPerceptron $mlp */
        $mlp = $this->getMockForAbstractClass(
            MultilayerPerceptron::class,
            [5, [3], [0, 1], 1000, null, 0.42]
        );

        $this->assertEquals(0.42, $this->readAttribute($mlp, 'learningRate'));
        $backprop = $this->readAttribute($mlp, 'backpropagation');
        $this->assertEquals(0.42, $this->readAttribute($backprop, 'learningRate'));

        $mlp->setLearningRate(0.24);
        $this->assertEquals(0.24, $this->readAttribute($mlp, 'learningRate'));
        $backprop = $this->readAttribute($mlp, 'backpropagation');
        $this->assertEquals(0.24, $this->readAttribute($backprop, 'learningRate'));
    }

    public function testLearningRateSetterWithCustomActivationFunctions(): void
    {
        $activation_function = $this->getActivationFunctionMock();

        /** @var MultilayerPerceptron $mlp */
        $mlp = $this->getMockForAbstractClass(
            MultilayerPerceptron::class,
            [5, [[3, $activation_function], [5, $activation_function]], [0, 1], 1000, null, 0.42]
        );

        $this->assertEquals(0.42, $this->readAttribute($mlp, 'learningRate'));
        $backprop = $this->readAttribute($mlp, 'backpropagation');
        $this->assertEquals(0.42, $this->readAttribute($backprop, 'learningRate'));

        $mlp->setLearningRate(0.24);
        $this->assertEquals(0.24, $this->readAttribute($mlp, 'learningRate'));
        $backprop = $this->readAttribute($mlp, 'backpropagation');
        $this->assertEquals(0.24, $this->readAttribute($backprop, 'learningRate'));
    }

    public function testLearningRateSetterWithLayerObject(): void
    {
        $activation_function = $this->getActivationFunctionMock();

        /** @var MultilayerPerceptron $mlp */
        $mlp = $this->getMockForAbstractClass(
            MultilayerPerceptron::class,
            [5, [new Layer(3, Neuron::class, $activation_function), new Layer(5, Neuron::class, $activation_function)], [0, 1], 1000, null, 0.42]
        );

        $this->assertEquals(0.42, $this->readAttribute($mlp, 'learningRate'));
        $backprop = $this->readAttribute($mlp, 'backpropagation');
        $this->assertEquals(0.42, $this->readAttribute($backprop, 'learningRate'));

        $mlp->setLearningRate(0.24);
        $this->assertEquals(0.24, $this->readAttribute($mlp, 'learningRate'));
        $backprop = $this->readAttribute($mlp, 'backpropagation');
        $this->assertEquals(0.24, $this->readAttribute($backprop, 'learningRate'));
    }

    /**
     * @return ActivationFunction|PHPUnit_Framework_MockObject_MockObject
     */
    private function getActivationFunctionMock()
    {
        return $this->getMockForAbstractClass(ActivationFunction::class);
    }
}
