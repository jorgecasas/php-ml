<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\Node\Neuron;

use Phpml\NeuralNetwork\Node\Neuron;
use Phpml\NeuralNetwork\Node\Neuron\Synapse;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class SynapseTest extends TestCase
{
    public function testSynapseInitialization(): void
    {
        $node = $this->getNodeMock($nodeOutput = 0.5);

        $synapse = new Synapse($node, $weight = 0.75);

        $this->assertEquals($node, $synapse->getNode());
        $this->assertEquals($weight, $synapse->getWeight());
        $this->assertEquals($weight * $nodeOutput, $synapse->getOutput());

        $synapse = new Synapse($node);

        $this->assertInternalType('float', $synapse->getWeight());
    }

    public function testSynapseWeightChange(): void
    {
        $node = $this->getNodeMock();
        $synapse = new Synapse($node, $weight = 0.75);
        $synapse->changeWeight(1.0);

        $this->assertEquals(1.75, $synapse->getWeight());

        $synapse->changeWeight(-2.0);

        $this->assertEquals(-0.25, $synapse->getWeight());
    }

    /**
     * @param int|float $output
     *
     * @return Neuron|PHPUnit_Framework_MockObject_MockObject
     */
    private function getNodeMock($output = 1)
    {
        $node = $this->getMockBuilder(Neuron::class)->getMock();
        $node->method('getOutput')->willReturn($output);

        return $node;
    }
}
