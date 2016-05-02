<?php

declare (strict_types = 1);

namespace Phpml\Classification;

use Phpml\Classification\Traits\Predictable;
use Phpml\Classification\Traits\Trainable;
use Phpml\Math\Kernel;

class SupportVectorMachine implements Classifier
{
    use Trainable, Predictable;

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var float
     */
    private $C;

    /**
     * @var float
     */
    private $tolerance;

    /**
     * @var int
     */
    private $upperBound;

    /**
     * @param Kernel $kernel
     * @param float  $C
     * @param float  $tolerance
     * @param int    $upperBound
     */
    public function __construct(Kernel $kernel = null, float $C = 1.0, float $tolerance = .001, int $upperBound = 100)
    {
        if (null === $kernel) {
            $kernel = new Kernel\RBF($gamma = .001);
        }

        $this->kernel = $kernel;
        $this->C = $C;
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
