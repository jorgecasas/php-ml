<?php

declare(strict_types=1);

namespace Phpml\NeuralNetwork\Network;

use Phpml\Estimator;
use Phpml\Exception\InvalidArgumentException;
use Phpml\Helper\Predictable;
use Phpml\IncrementalEstimator;
use Phpml\NeuralNetwork\ActivationFunction;
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Node\Bias;
use Phpml\NeuralNetwork\Node\Input;
use Phpml\NeuralNetwork\Node\Neuron;
use Phpml\NeuralNetwork\Node\Neuron\Synapse;
use Phpml\NeuralNetwork\Training\Backpropagation;

abstract class MultilayerPerceptron extends LayeredNetwork implements Estimator, IncrementalEstimator
{
    use Predictable;

    /**
     * @var int
     */
    private $inputLayerFeatures;

    /**
     * @var array
     */
    private $hiddenLayers;

    /**
     * @var array
     */
    protected $classes = [];

    /**
     * @var int
     */
    private $iterations;

    /**
     * @var ActivationFunction
     */
    protected $activationFunction;

    /**
     * @var int
     */
    private $theta;

    /**
     * @var Backpropagation
     */
    protected $backpropagation = null;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(int $inputLayerFeatures, array $hiddenLayers, array $classes, int $iterations = 10000, ?ActivationFunction $activationFunction = null, int $theta = 1)
    {
        if (empty($hiddenLayers)) {
            throw InvalidArgumentException::invalidLayersNumber();
        }

        if (count($classes) < 2) {
            throw InvalidArgumentException::invalidClassesNumber();
        }

        $this->classes = array_values($classes);
        $this->iterations = $iterations;
        $this->inputLayerFeatures = $inputLayerFeatures;
        $this->hiddenLayers = $hiddenLayers;
        $this->activationFunction = $activationFunction;
        $this->theta = $theta;

        $this->initNetwork();
    }

    private function initNetwork(): void
    {
        $this->addInputLayer($this->inputLayerFeatures);
        $this->addNeuronLayers($this->hiddenLayers, $this->activationFunction);
        $this->addNeuronLayers([count($this->classes)], $this->activationFunction);

        $this->addBiasNodes();
        $this->generateSynapses();

        $this->backpropagation = new Backpropagation($this->theta);
    }

    public function train(array $samples, array $targets): void
    {
        $this->reset();
        $this->initNetwork();
        $this->partialTrain($samples, $targets, $this->classes);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function partialTrain(array $samples, array $targets, array $classes = []): void
    {
        if (!empty($classes) && array_values($classes) !== $this->classes) {
            // We require the list of classes in the constructor.
            throw InvalidArgumentException::inconsistentClasses();
        }

        for ($i = 0; $i < $this->iterations; ++$i) {
            $this->trainSamples($samples, $targets);
        }
    }

    /**
     * @param mixed $target
     */
    abstract protected function trainSample(array $sample, $target);

    /**
     * @return mixed
     */
    abstract protected function predictSample(array $sample);

    protected function reset(): void
    {
        $this->removeLayers();
    }

    private function addInputLayer(int $nodes): void
    {
        $this->addLayer(new Layer($nodes, Input::class));
    }

    private function addNeuronLayers(array $layers, ?ActivationFunction $activationFunction = null): void
    {
        foreach ($layers as $neurons) {
            $this->addLayer(new Layer($neurons, Neuron::class, $activationFunction));
        }
    }

    private function generateSynapses(): void
    {
        $layersNumber = count($this->layers) - 1;
        for ($i = 0; $i < $layersNumber; ++$i) {
            $currentLayer = $this->layers[$i];
            $nextLayer = $this->layers[$i + 1];
            $this->generateLayerSynapses($nextLayer, $currentLayer);
        }
    }

    private function addBiasNodes(): void
    {
        $biasLayers = count($this->layers) - 1;
        for ($i = 0; $i < $biasLayers; ++$i) {
            $this->layers[$i]->addNode(new Bias());
        }
    }

    private function generateLayerSynapses(Layer $nextLayer, Layer $currentLayer): void
    {
        foreach ($nextLayer->getNodes() as $nextNeuron) {
            if ($nextNeuron instanceof Neuron) {
                $this->generateNeuronSynapses($currentLayer, $nextNeuron);
            }
        }
    }

    private function generateNeuronSynapses(Layer $currentLayer, Neuron $nextNeuron): void
    {
        foreach ($currentLayer->getNodes() as $currentNeuron) {
            $nextNeuron->addSynapse(new Synapse($currentNeuron));
        }
    }

    private function trainSamples(array $samples, array $targets): void
    {
        foreach ($targets as $key => $target) {
            $this->trainSample($samples[$key], $target);
        }
    }
}
