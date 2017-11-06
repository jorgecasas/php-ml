<?php

declare(strict_types=1);

namespace Phpml\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction;

class HyperbolicTangent implements ActivationFunction
{
    /**
     * @var float
     */
    private $beta;

    public function __construct(float $beta = 1.0)
    {
        $this->beta = $beta;
    }

    /**
     * @param float|int $value
     */
    public function compute($value) : float
    {
        return tanh($this->beta * $value);
    }
}
