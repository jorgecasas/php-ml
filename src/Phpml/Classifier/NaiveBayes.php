<?php

namespace Phpml\Classifier;

abstract class NaiveBayes implements Classifier
{

    /**
     * @param array $features
     * @param array $labels
     */
    public function train($features, $labels)
    {

    }

    /**
     * @param mixed $feature
     * @return mixed
     */
    public function predict($feature)
    {

    }

}
