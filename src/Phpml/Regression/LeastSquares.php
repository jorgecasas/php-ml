<?php

declare (strict_types = 1);

namespace Phpml\Regression;

class LeastSquares implements Regression
{
    /**
     * @var array
     */
    private $features;

    /**
     * @var array
     */
    private $targets;

    /**
     * @var float
     */
    private $slope;

    /**
     * @var
     */
    private $intercept;

    /**
     * @param array $features
     * @param array $targets
     */
    public function train(array $features, array $targets)
    {
        $this->features = $features;
        $this->targets = $targets;
    }

    /**
     * @param array $features
     *
     * @return mixed
     */
    public function predict(array $features)
    {
    }
}
