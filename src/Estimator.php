<?php

declare(strict_types=1);

namespace Phpml;

interface Estimator
{
    public function train(array $samples, array $targets);

    /**
     * @return mixed
     */
    public function predict(array $samples);
}
