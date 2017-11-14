<?php

declare(strict_types=1);

namespace Phpml\NeuralNetwork;

use Phpml\Exception\InvalidArgumentException;
use Phpml\NeuralNetwork\Node\Neuron;

class Layer
{
    /**
     * @var Node[]
     */
    private $nodes = [];

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(int $nodesNumber = 0, string $nodeClass = Neuron::class, ?ActivationFunction $activationFunction = null)
    {
        if (!in_array(Node::class, class_implements($nodeClass))) {
            throw InvalidArgumentException::invalidLayerNodeClass();
        }

        for ($i = 0; $i < $nodesNumber; ++$i) {
            $this->nodes[] = $this->createNode($nodeClass, $activationFunction);
        }
    }

    /**
     * @param ActivationFunction|null $activationFunction
     *
     * @return Neuron
     */
    private function createNode(string $nodeClass, ?ActivationFunction $activationFunction = null)
    {
        if (Neuron::class == $nodeClass) {
            return new Neuron($activationFunction);
        }

        return new $nodeClass();
    }

    public function addNode(Node $node): void
    {
        $this->nodes[] = $node;
    }

    /**
     * @return Node[]
     */
    public function getNodes() : array
    {
        return $this->nodes;
    }
}
