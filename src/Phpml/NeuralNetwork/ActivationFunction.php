<?php

declare(strict_types=1);

namespace Phpml\NeuralNetwork;

interface ActivationFunction
{
    /**
     * @param float|int $value
     */
    public function compute($value) : float;
}
