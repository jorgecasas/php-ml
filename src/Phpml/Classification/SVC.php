<?php

declare (strict_types = 1);

namespace Phpml\Classification;

use Phpml\Classification\Traits\Predictable;
use Phpml\Classification\Traits\Trainable;
use Phpml\Math\Kernel;

class SVC implements Classifier
{
    use Trainable, Predictable;

    /**
     * @var int
     */
    private $kernel;

    /**
     * @var float
     */
    private $cost;

    /**
     * @param int   $kernel
     * @param float $cost
     */
    public function __construct(int $kernel, float $cost)
    {
        $this->kernel = $kernel;
        $this->cost = $cost;
    }

    /**
     * @param array $samples
     * @param array $labels
     */
    public function train(array $samples, array $labels)
    {
        $this->samples = $samples;
        $this->labels = $labels;
    }

    /**
     * @param array $sample
     *
     * @return mixed
     */
    protected function predictSample(array $sample)
    {
    }
}
