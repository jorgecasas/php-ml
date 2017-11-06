<?php

declare(strict_types=1);

namespace Phpml\NeuralNetwork\ActivationFunction;

use Phpml\NeuralNetwork\ActivationFunction;

class ThresholdedReLU implements ActivationFunction
{
    /**
     * @var float
     */
    private $theta;

    public function __construct(float $theta = 1.0)
    {
        $this->theta = $theta;
    }

    /**
     * @param float|int $value
     */
    public function compute($value) : float
    {
        return $value > $this->theta ? $value : 0.0;
    }
}
