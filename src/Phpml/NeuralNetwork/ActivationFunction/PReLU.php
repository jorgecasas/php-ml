<?php

declare(strict_types=1);

namespace Phpml\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction;

class PReLU implements ActivationFunction
{
    /**
     * @var float
     */
    private $beta;

    public function __construct(float $beta = 0.01)
    {
        $this->beta = $beta;
    }

    /**
     * @param float|int $value
     */
    public function compute($value) : float
    {
        return $value >= 0 ? $value : $this->beta * $value;
    }
}
