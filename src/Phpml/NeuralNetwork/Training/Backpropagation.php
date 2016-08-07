<?php

declare (strict_types = 1);

namespace Phpml\NeuralNetwork\Training;

use Phpml\NeuralNetwork\Training;

class Backpropagation implements Training
{
    /**
     * @param array $samples
     * @param array $targets
     * @param float $desiredError
     * @param int   $maxIterations
     */
    public function train(array $samples, array $targets, float $desiredError = 0.001, int $maxIterations = 10000)
    {
    }
}
