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
    private $samples;

    /**
     * @var array
     */
    private $features;

    /**
     * @var array
     */
    private $targets;

    /**
     * @var array
     */
    private $slopes;

    /**
     * @var float
     */
    private $intercept;

    /**
     * @param array $samples
     * @param array $targets
     */
    public function train(array $samples, array $targets)
    {
        $this->samples = $samples;
        $this->targets = $targets;
        $this->features = [];

        $this->computeSlopes();
        $this->computeIntercept();
    }

    /**
     * @param float $sample
     *
     * @return mixed
     */
    public function predict($sample)
    {
        $result = $this->intercept;
        foreach ($this->slopes as $index => $slope) {
            $result += ($slope * $sample[$index]);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getSlopes()
    {
        return $this->slopes;
    }

    private function computeSlopes()
    {
        $features = count($this->samples[0]);
        $sdY = StandardDeviation::population($this->targets);

        for($i=0; $i<$features; $i++) {
            $correlation = Correlation::pearson($this->getFeatures($i), $this->targets);
            $sdXi = StandardDeviation::population($this->getFeatures($i));
            $this->slopes[] = $correlation * ($sdY / $sdXi);
        }
    }

    private function computeIntercept()
    {
        $this->intercept = Mean::arithmetic($this->targets);
        foreach ($this->slopes as $index => $slope) {
            $this->intercept -= $slope * Mean::arithmetic($this->getFeatures($index));
        }
    }

    /**
     * @param $index
     *
     * @return array
     */
    private function getFeatures($index)
    {
        if(!isset($this->features[$index])) {
            $this->features[$index] = [];
            foreach ($this->samples as $sample) {
                $this->features[$index][] = $sample[$index];
            }
        }

        return $this->features[$index];
    }
}
