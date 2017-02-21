<?php

declare(strict_types=1);

namespace Phpml\Classification\Ensemble;

use Phpml\Classification\Linear\DecisionStump;
use Phpml\Classification\Classifier;
use Phpml\Helper\Predictable;
use Phpml\Helper\Trainable;

class AdaBoost implements Classifier
{
    use Predictable, Trainable;

    /**
     * Actual labels given in the targets array
     * @var array
     */
    protected $labels = [];

    /**
     * @var int
     */
    protected $sampleCount;

    /**
     * @var int
     */
    protected $featureCount;

    /**
     * Number of maximum iterations to be done
     *
     * @var int
     */
    protected $maxIterations;

    /**
     * Sample weights
     *
     * @var array
     */
    protected $weights = [];

    /**
     * Base classifiers
     *
     * @var array
     */
    protected $classifiers = [];

    /**
     * Base classifier weights
     *
     * @var array
     */
    protected $alpha = [];

    /**
     * ADAptive BOOSTing (AdaBoost) is an ensemble algorithm to
     * improve classification performance of 'weak' classifiers such as
     * DecisionStump (default base classifier of AdaBoost).
     *
     */
    public function __construct(int $maxIterations = 30)
    {
        $this->maxIterations = $maxIterations;
    }

    /**
     * @param array $samples
     * @param array $targets
     */
    public function train(array $samples, array $targets)
    {
        // Initialize usual variables
        $this->labels = array_keys(array_count_values($targets));
        if (count($this->labels) != 2) {
            throw new \Exception("AdaBoost is a binary classifier and can only classify between two classes");
        }

        // Set all target values to either -1 or 1
        $this->labels = [1 => $this->labels[0], -1 => $this->labels[1]];
        foreach ($targets as $target) {
            $this->targets[] = $target == $this->labels[1] ? 1 : -1;
        }

        $this->samples = array_merge($this->samples, $samples);
        $this->featureCount = count($samples[0]);
        $this->sampleCount = count($this->samples);

        // Initialize AdaBoost parameters
        $this->weights = array_fill(0, $this->sampleCount, 1.0 / $this->sampleCount);
        $this->classifiers = [];
        $this->alpha = [];

        // Execute the algorithm for a maximum number of iterations
        $currIter = 0;
        while ($this->maxIterations > $currIter++) {
            // Determine the best 'weak' classifier based on current weights
            // and update alpha & weight values at each iteration
            list($classifier, $errorRate) = $this->getBestClassifier();
            $alpha = $this->calculateAlpha($errorRate);
            $this->updateWeights($classifier, $alpha);

            $this->classifiers[] = $classifier;
            $this->alpha[] = $alpha;
        }
    }

    /**
     * Returns the classifier with the lowest error rate with the
     * consideration of current sample weights
     *
     * @return Classifier
     */
    protected function getBestClassifier()
    {
        // This method works only for "DecisionStump" classifier, for now.
        // As a future task, it will be generalized enough to work with other
        //  classifiers as well
        $minErrorRate = 1.0;
        $bestClassifier = null;
        for ($i=0; $i < $this->featureCount; $i++) {
            $stump = new DecisionStump($i);
            $stump->setSampleWeights($this->weights);
            $stump->train($this->samples, $this->targets);

            $errorRate = $stump->getTrainingErrorRate();
            if ($errorRate < $minErrorRate) {
                $bestClassifier = $stump;
                $minErrorRate = $errorRate;
            }
        }

        return [$bestClassifier, $minErrorRate];
    }

    /**
     * Calculates alpha of a classifier
     *
     * @param float $errorRate
     * @return float
     */
    protected function calculateAlpha(float $errorRate)
    {
        if ($errorRate == 0) {
            $errorRate = 1e-10;
        }
        return 0.5 * log((1 - $errorRate) / $errorRate);
    }

    /**
     * Updates the sample weights
     *
     * @param DecisionStump $classifier
     * @param float $alpha
     */
    protected function updateWeights(DecisionStump $classifier, float $alpha)
    {
        $sumOfWeights = array_sum($this->weights);
        $weightsT1 = [];
        foreach ($this->weights as $index => $weight) {
            $desired = $this->targets[$index];
            $output = $classifier->predict($this->samples[$index]);

            $weight *= exp(-$alpha * $desired * $output) / $sumOfWeights;

            $weightsT1[] = $weight;
        }

        $this->weights = $weightsT1;
    }

    /**
     * @param array $sample
     * @return mixed
     */
    public function predictSample(array $sample)
    {
        $sum = 0;
        foreach ($this->alpha as $index => $alpha) {
            $h = $this->classifiers[$index]->predict($sample);
            $sum += $h * $alpha;
        }

        return $this->labels[ $sum > 0 ? 1 : -1];
    }
}
