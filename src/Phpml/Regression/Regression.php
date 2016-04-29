<?php

declare (strict_types = 1);

namespace Phpml\Regression;

interface Regression
{
    /**
     * @param array $samples
     * @param array $targets
     */
    public function train(array $samples, array $targets);

    /**
     * @param float $sample
     *
     * @return mixed
     */
    public function predict($sample);
}
