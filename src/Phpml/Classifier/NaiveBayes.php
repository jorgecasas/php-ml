<?php

declare (strict_types = 1);

namespace Phpml\Classifier;

class NaiveBayes implements Classifier
{
    /**
     * @param array $samples
     * @param array $labels
     */
    public function train(array $samples, array $labels)
    {
    }

    /**
     * @param array $samples
     *
     * @return mixed
     */
    public function predict(array $samples)
    {
    }
}
