<?php

declare (strict_types = 1);

namespace Phpml\Classifier;

use Phpml\Classifier\Traits\Predictable;
use Phpml\Classifier\Traits\Trainable;

class SupportVectorMachine implements Classifier
{
    use Trainable, Predictable;

    /**
     * @param array $sample
     *
     * @return mixed
     */
    protected function predictSample(array $sample)
    {
    }
}
