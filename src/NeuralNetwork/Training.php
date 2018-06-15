<?php

declare(strict_types=1);

namespace Phpml\NeuralNetwork;

interface Training
{
    public function train(array $samples, array $targets);
}
