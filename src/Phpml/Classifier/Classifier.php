<?php

namespace Phpml\Classifier;

interface Classifier
{

    /**
     * @param array $features
     * @param array $labels
     */
    public function train($features, $labels);

    /**
     * @param mixed $feature
     * @return mixed
     */
    public function predict($feature);

}
