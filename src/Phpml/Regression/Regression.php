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
     * @param array $features
     *
     * @return mixed
     */
    public function predict(array $features);
}
