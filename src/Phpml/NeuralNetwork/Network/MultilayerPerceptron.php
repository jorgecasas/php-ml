<?php

declare(strict_types=1);

namespace Phpml\NeuralNetwork\Network;

use Phpml\Estimator;
use Phpml\Exception\InvalidArgumentException;
use Phpml\NeuralNetwork\Training\Backpropagation;
use Phpml\NeuralNetwork\ActivationFunction;
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Node\Bias;
use Phpml\NeuralNetwork\Node\Input;
use Phpml\NeuralNetwork\Node\Neuron;
use Phpml\NeuralNetwork\Node\Neuron\Synapse;
use Phpml\Helper\Predictable;

abstract class MultilayerPerceptron extends LayeredNetwork implements Estimator
{
    use Predictable;

    /**
     * @var array
     */
    protected $classes = [];

    /**
     * @var int
     */
    private $iterations;

    /**
     * @var Backpropagation
     */
    protected $backpropagation = null;

    /**
     * @param int                     $inputLayerFeatures
     * @param array                   $hiddenLayers
     * @param array                   $classes
     * @param int                     $iterations
     * @param ActivationFunction|null $activationFunction
     * @param int                     $theta
     *
     * @throws InvalidArgumentException
     */
    public function __construct(int $inputLayerFeatures, array $hiddenLayers, array $classes, int $iterations = 10000, ActivationFunction $activationFunction = null, int $theta = 1)
    {
        if (empty($hiddenLayers)) {
            throw InvalidArgumentException::invalidLayersNumber();
        }

        $nClasses = count($classes);
        if ($nClasses < 2) {
            throw InvalidArgumentException::invalidClassesNumber();
        }
        $this->classes = array_values($classes);

        $this->iterations = $iterations;

        $this->addInputLayer($inputLayerFeatures);
        $this->addNeuronLayers($hiddenLayers, $activationFunction);
        $this->addNeuronLayers([$nClasses], $activationFunction);

        $this->addBiasNodes();
        $this->generateSynapses();

        $this->backpropagation = new Backpropagation($theta);
    }

    /**
     * @param array $samples
     * @param array $targets
     */
    public function train(array $samples, array $targets)
    {
        for ($i = 0; $i < $this->iterations; ++$i) {
            $this->trainSamples($samples, $targets);
        }
    }

    /**
     * @param array $sample
     * @param mixed $target
     */
    protected abstract function trainSample(array $sample, $target);

    /**
     * @param array $sample
     * @return mixed
     */
    protected abstract function predictSample(array $sample);

    /**
     * @param int $nodes
     */
    private function addInputLayer(int $nodes)
    {
        $this->addLayer(new Layer($nodes, Input::class));
    }

    /**
     * @param array                   $layers
     * @param ActivationFunction|null $activationFunction
     */
    private function addNeuronLayers(array $layers, ActivationFunction $activationFunction = null)
    {
        foreach ($layers as $neurons) {
            $this->addLayer(new Layer($neurons, Neuron::class, $activationFunction));
        }
    }

    private function generateSynapses()
    {
        $layersNumber = count($this->layers) - 1;
        for ($i = 0; $i < $layersNumber; ++$i) {
            $currentLayer = $this->layers[$i];
            $nextLayer = $this->layers[$i + 1];
            $this->generateLayerSynapses($nextLayer, $currentLayer);
        }
    }

    private function addBiasNodes()
    {
        $biasLayers = count($this->layers) - 1;
        for ($i = 0; $i < $biasLayers; ++$i) {
            $this->layers[$i]->addNode(new Bias());
        }
    }

    /**
     * @param Layer $nextLayer
     * @param Layer $currentLayer
     */
    private function generateLayerSynapses(Layer $nextLayer, Layer $currentLayer)
    {
        foreach ($nextLayer->getNodes() as $nextNeuron) {
            if ($nextNeuron instanceof Neuron) {
                $this->generateNeuronSynapses($currentLayer, $nextNeuron);
            }
        }
    }

    /**
     * @param Layer  $currentLayer
     * @param Neuron $nextNeuron
     */
    private function generateNeuronSynapses(Layer $currentLayer, Neuron $nextNeuron)
    {
        foreach ($currentLayer->getNodes() as $currentNeuron) {
            $nextNeuron->addSynapse(new Synapse($currentNeuron));
        }
    }

    /**
     * @param array $samples
     * @param array $targets
     */
    private function trainSamples(array $samples, array $targets)
    {
        foreach ($targets as $key => $target) {
            $this->trainSample($samples[$key], $target);
        }
    }
}
