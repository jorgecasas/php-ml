<?php

declare(strict_types=1);

namespace Phpml\NeuralNetwork\Node;

use Phpml\NeuralNetwork\ActivationFunction;
use Phpml\NeuralNetwork\Node;
use Phpml\NeuralNetwork\Node\Neuron\Synapse;

class Neuron implements Node
{
    /**
     * @var Synapse[]
     */
    protected $synapses;

    /**
     * @var ActivationFunction
     */
    protected $activationFunction;

    /**
     * @var float
     */
    protected $output;

    public function __construct(?ActivationFunction $activationFunction = null)
    {
        $this->activationFunction = $activationFunction ?: new ActivationFunction\Sigmoid();
        $this->synapses = [];
        $this->output = 0;
    }

    public function addSynapse(Synapse $synapse): void
    {
        $this->synapses[] = $synapse;
    }

    /**
     * @return Synapse[]
     */
    public function getSynapses()
    {
        return $this->synapses;
    }

    public function getOutput() : float
    {
        if (0 === $this->output) {
            $sum = 0;
            foreach ($this->synapses as $synapse) {
                $sum += $synapse->getOutput();
            }

            $this->output = $this->activationFunction->compute($sum);
        }

        return $this->output;
    }

    public function reset(): void
    {
        $this->output = 0;
    }
}
