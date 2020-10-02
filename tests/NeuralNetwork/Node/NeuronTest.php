<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\Node;

use Phpml\NeuralNetwork\ActivationFunction\BinaryStep;
use Phpml\NeuralNetwork\Node\Neuron;
use Phpml\NeuralNetwork\Node\Neuron\Synapse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NeuronTest extends TestCase
{
    public function testNeuronInitialization(): void
    {
        $neuron = new Neuron();

        self::assertEquals([], $neuron->getSynapses());
        self::assertEquals(0.5, $neuron->getOutput());
    }

    public function testNeuronActivationFunction(): void
    {
        /** @var BinaryStep|MockObject $activationFunction */
        $activationFunction = $this->getMockBuilder(BinaryStep::class)->getMock();
        $activationFunction->method('compute')->with(0)->willReturn($output = 0.69);

        $neuron = new Neuron($activationFunction);

        self::assertEquals($output, $neuron->getOutput());
    }

    public function testNeuronWithSynapse(): void
    {
        $neuron = new Neuron();
        $neuron->addSynapse($synapse = $this->getSynapseMock());

        self::assertEquals([$synapse], $neuron->getSynapses());
        self::assertEqualsWithDelta(0.88, $neuron->getOutput(), 0.01);
    }

    public function testNeuronRefresh(): void
    {
        $neuron = new Neuron();
        $neuron->getOutput();
        $neuron->addSynapse($this->getSynapseMock());

        self::assertEqualsWithDelta(0.5, $neuron->getOutput(), 0.01);

        $neuron->reset();

        self::assertEqualsWithDelta(0.88, $neuron->getOutput(), 0.01);
    }

    /**
     * @return Synapse|MockObject
     */
    private function getSynapseMock(float $output = 2.)
    {
        $synapse = $this->getMockBuilder(Synapse::class)->disableOriginalConstructor()->getMock();
        $synapse->method('getOutput')->willReturn($output);

        return $synapse;
    }
}
