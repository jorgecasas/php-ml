<?php

declare (strict_types = 1);

namespace Phpml\NeuralNetwork\Training;

use Phpml\NeuralNetwork\Network;
use Phpml\NeuralNetwork\Node\Neuron;
use Phpml\NeuralNetwork\Training;
use Phpml\NeuralNetwork\Training\Backpropagation\Sigma;

class Backpropagation implements Training
{
    /**
     * @var Network
     */
    private $network;

    /**
     * @var int
     */
    private $theta;

    /**
     * @param Network $network
     * @param int     $theta
     */
    public function __construct(Network $network, int $theta = 1)
    {
        $this->network = $network;
        $this->theta = $theta;
    }

    /**
     * @param array $samples
     * @param array $targets
     * @param float $desiredError
     * @param int   $maxIterations
     */
    public function train(array $samples, array $targets, float $desiredError = 0.001, int $maxIterations = 10000)
    {
        for ($i = 0; $i < $maxIterations; ++$i) {
            $resultsWithinError = $this->trainSamples($samples, $targets, $desiredError);

            if ($resultsWithinError == count($samples)) {
                break;
            }
        }
    }

    /**
     * @param array $samples
     * @param array $targets
     * @param float $desiredError
     *
     * @return int
     */
    private function trainSamples(array $samples, array $targets, float $desiredError): int
    {
        $resultsWithinError = 0;
        foreach ($targets as $key => $target) {
            $result = $this->network->setInput($samples[$key])->getOutput();

            if ($this->isResultWithinError($result, $target, $desiredError)) {
                ++$resultsWithinError;
            } else {
                $this->trainSample($samples[$key], $target);
            }
        }

        return $resultsWithinError;
    }

    private function trainSample(array $sample, array $target)
    {
        $this->network->setInput($sample)->getOutput();

        $sigmas = [];
        $layers = $this->network->getLayers();
        $layersNumber = count($layers);

        for ($i = $layersNumber; $i > 1; --$i) {
            foreach ($layers[$i - 1]->getNodes() as $key => $neuron) {
                if ($neuron instanceof Neuron) {
                    $neuronOutput = $neuron->getOutput();
                    $sigma = $neuronOutput * (1 - $neuronOutput) * ($i == $layersNumber ? ($target[$key] - $neuronOutput) : $this->getPrevSigma($sigmas, $neuron));
                    $sigmas[] = new Sigma($neuron, $sigma);
                    foreach ($neuron->getSynapses() as $synapse) {
                        $synapse->changeWeight($this->theta * $sigma * $synapse->getNode()->getOutput());
                    }
                }
            }
        }
    }

    /**
     * @param Sigma[] $sigmas
     * @param Neuron  $forNeuron
     * 
     * @return float
     */
    private function getPrevSigma(array $sigmas, Neuron $forNeuron): float
    {
        $sigma = 0.0;

        foreach ($sigmas as $neuronSigma) {
            foreach ($neuronSigma->getNeuron()->getSynapses() as $synapse) {
                if ($synapse->getNode() == $forNeuron) {
                    $sigma += $synapse->getWeight() * $neuronSigma->getSigma();
                }
            }
        }

        return $sigma;
    }

    /**
     * @param array $result
     * @param array $target
     * @param float $desiredError
     *
     * @return bool
     */
    private function isResultWithinError(array $result, array $target, float $desiredError)
    {
        foreach ($target as $key => $value) {
            if ($result[$key] > $value + $desiredError || $result[$key] < $value - $desiredError) {
                return false;
            }
        }

        return true;
    }
}
