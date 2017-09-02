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

    /**
     * @param float $theta
     */
    public function __construct($theta = 1.0)
    {
        $this->theta = $theta;
    }

    /**
     * @param float|int $value
     *
     * @return float
     */
    public function compute($value): float
    {
        return $value > $this->theta ? $value : 0.0;
    }
}
