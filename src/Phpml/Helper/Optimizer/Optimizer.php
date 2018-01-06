<?php

declare(strict_types=1);

namespace Phpml\Helper\Optimizer;

use Closure;
use Exception;

abstract class Optimizer
{
    /**
     * Unknown variables to be found
     *
     * @var array
     */
    protected $theta = [];

    /**
     * Number of dimensions
     *
     * @var int
     */
    protected $dimensions;

    /**
     * Inits a new instance of Optimizer for the given number of dimensions
     */
    public function __construct(int $dimensions)
    {
        $this->dimensions = $dimensions;

        // Inits the weights randomly
        $this->theta = [];
        for ($i = 0; $i < $this->dimensions; ++$i) {
            $this->theta[] = random_int(0, getrandmax()) / (float) getrandmax();
        }
    }

    /**
     * Sets the weights manually
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function setInitialTheta(array $theta)
    {
        if (count($theta) != $this->dimensions) {
            throw new Exception("Number of values in the weights array should be ${this}->dimensions");
        }

        $this->theta = $theta;

        return $this;
    }

    /**
     * Executes the optimization with the given samples & targets
     * and returns the weights
     */
    abstract public function runOptimization(array $samples, array $targets, Closure $gradientCb);
}
