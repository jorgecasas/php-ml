<?php

declare (strict_types = 1);

namespace Phpml\Regression;

use Phpml\Math\Matrix;

class LeastSquares implements Regression
{
    /**
     * @var array
     */
    private $samples;

    /**
     * @var array
     */
    private $targets;

    /**
     * @var float
     */
    private $intercept;

    /**
     * @var array
     */
    private $coefficients;

    /**
     * @param array $samples
     * @param array $targets
     */
    public function train(array $samples, array $targets)
    {
        $this->samples = $samples;
        $this->targets = $targets;

        $this->computeCoefficients();
    }

    /**
     * @param array $sample
     *
     * @return mixed
     */
    public function predict($sample)
    {
        $result = $this->intercept;
        foreach ($this->coefficients as $index => $coefficient) {
            $result += $coefficient * $sample[$index];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getCoefficients()
    {
        return $this->coefficients;
    }

    /**
     * @return float
     */
    public function getIntercept()
    {
        return $this->intercept;
    }

    /**
     * coefficient(b) = (X'X)-1X'Y.
     */
    private function computeCoefficients()
    {
        $samplesMatrix = new Matrix($this->samples);
        $targetsMatrix = new Matrix($this->targets);

        $ts = $samplesMatrix->transpose()->multiply($samplesMatrix)->inverse();
        $tf = $samplesMatrix->transpose()->multiply($targetsMatrix);

        $this->coefficients = $ts->multiply($tf)->getColumnValues(0);
        $this->intercept = array_shift($this->coefficients);
    }
}
