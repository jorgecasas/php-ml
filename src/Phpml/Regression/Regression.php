<?php

declare (strict_types = 1);

namespace Phpml\Regression;

interface Regression
{
    /**
     * @param array $features
     * @param array $targets
     */
    public function train(array $features, array $targets);

    /**
     * @param float $feature
     *
     * @return mixed
     */
    public function predict($feature);
}
