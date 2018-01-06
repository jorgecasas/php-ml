<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\Network;

use Phpml\NeuralNetwork\Network\MultilayerPerceptron;
use PHPUnit\Framework\TestCase;

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
}
