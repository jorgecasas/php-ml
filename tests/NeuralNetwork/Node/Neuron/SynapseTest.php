<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\Node\Neuron;

use Phpml\NeuralNetwork\Node\Neuron;
use Phpml\NeuralNetwork\Node\Neuron\Synapse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SynapseTest extends TestCase
{
    public function testSynapseInitialization(): void
    {
        $node = $this->getNodeMock($nodeOutput = 0.5);

        $synapse = new Synapse($node, $weight = 0.75);

        self::assertEquals($node, $synapse->getNode());
        self::assertEquals($weight, $synapse->getWeight());
        self::assertEquals($weight * $nodeOutput, $synapse->getOutput());

        $synapse = new Synapse($node);
        $weight = $synapse->getWeight();

        self::assertTrue($weight === -1. || $weight === 1.);
    }

    public function testSynapseWeightChange(): void
    {
        $node = $this->getNodeMock();
        $synapse = new Synapse($node, $weight = 0.75);
        $synapse->changeWeight(1.0);

        self::assertEquals(1.75, $synapse->getWeight());

        $synapse->changeWeight(-2.0);

        self::assertEquals(-0.25, $synapse->getWeight());
    }

    /**
     * @return Neuron|MockObject
     */
    private function getNodeMock(float $output = 1.)
    {
        $node = $this->getMockBuilder(Neuron::class)->getMock();
        $node->method('getOutput')->willReturn($output);

        return $node;
    }
}
