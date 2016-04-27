<?php

declare (strict_types = 1);

namespace Phpml\Regression;

use Phpml\Math\Statistic\Correlation;
use Phpml\Math\Statistic\StandardDeviation;
use Phpml\Math\Statistic\Mean;

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

        $this->computeSlope();
        $this->computeIntercept();
    }

    /**
     * @param float $feature
     *
     * @return mixed
     */
    public function predict($feature)
    {
        return $this->intercept + ($this->slope * $feature);
    }

    private function computeSlope()
    {
        $correlation = Correlation::pearson($this->features, $this->targets);
        $sdX = StandardDeviation::population($this->features);
        $sdY = StandardDeviation::population($this->targets);

        $this->slope = $correlation * ($sdY / $sdX);
    }

    private function computeIntercept()
    {
        $meanY = Mean::arithmetic($this->targets);
        $meanX = Mean::arithmetic($this->features);

        $this->intercept = $meanY - ($this->slope * $meanX);
    }
}
