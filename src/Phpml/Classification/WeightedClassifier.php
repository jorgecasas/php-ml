<?php declare(strict_types=1);

namespace Phpml\Classification;

use Phpml\Classification\Classifier;

abstract class WeightedClassifier implements Classifier
{
    protected $weights = null;

    /**
     * Sets the array including a weight for each sample
     *
     * @param array $weights
     */
    public function setSampleWeights(array $weights)
    {
        $this->weights = $weights;
    }
}
