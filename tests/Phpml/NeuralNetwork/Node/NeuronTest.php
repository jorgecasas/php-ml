<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\Node;

use Phpml\NeuralNetwork\ActivationFunction\BinaryStep;
use Phpml\NeuralNetwork\Node\Neuron;
use Phpml\NeuralNetwork\Node\Neuron\Synapse;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class NeuronTest extends TestCase
{
    public function testNeuronInitialization(): void
    {
        $neuron = new Neuron();

        $this->assertEquals([], $neuron->getSynapses());
        $this->assertEquals(0.5, $neuron->getOutput());
    }

    public function testNeuronActivationFunction(): void
    {
        /** @var BinaryStep|PHPUnit_Framework_MockObject_MockObject $activationFunction */
        $activationFunction = $this->getMockBuilder(BinaryStep::class)->getMock();
        $activationFunction->method('compute')->with(0)->willReturn($output = 0.69);

        $neuron = new Neuron($activationFunction);

        $this->assertEquals($output, $neuron->getOutput());
    }

    public function testNeuronWithSynapse(): void
    {
        $neuron = new Neuron();
        $neuron->addSynapse($synapse = $this->getSynapseMock());

        $this->assertEquals([$synapse], $neuron->getSynapses());
        $this->assertEquals(0.88, $neuron->getOutput(), '', 0.01);
    }

    public function testNeuronRefresh(): void
    {
        $neuron = new Neuron();
        $neuron->getOutput();
        $neuron->addSynapse($this->getSynapseMock());

        $this->assertEquals(0.5, $neuron->getOutput(), '', 0.01);

        $neuron->reset();

        $this->assertEquals(0.88, $neuron->getOutput(), '', 0.01);
    }

    /**
     * @return Synapse|PHPUnit_Framework_MockObject_MockObject
     */
    private function getSynapseMock(int $output = 2)
    {
        $synapse = $this->getMockBuilder(Synapse::class)->disableOriginalConstructor()->getMock();
        $synapse->method('getOutput')->willReturn($output);

        return $synapse;
    }
}
