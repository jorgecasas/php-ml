<?php

declare (strict_types = 1);

namespace Phpml\Classifier;

use Phpml\Classifier\Traits\Predictable;
use Phpml\Classifier\Traits\Trainable;

class SupportVectorMachine implements Classifier
{
    use Trainable, Predictable;

    /**
     * @var float
     */
    private $gamma;

    /**
     * @var float
     */
    private $epsilon;

    /**
     * @var float
     */
    private $tolerance;

    /**
     * @var int
     */
    private $upperBound;

    /**
     * @param float $gamma
     * @param float $epsilon
     * @param float $tolerance
     * @param int $upperBound
     */
    public function __construct(float $gamma = .5, float $epsilon = .001, float $tolerance = .001, int $upperBound = 100)
    {
        $this->gamma = $gamma;
        $this->epsilon = $epsilon;
        $this->tolerance = $tolerance;
        $this->upperBound = $upperBound;
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
