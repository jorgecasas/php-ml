<?php

declare(strict_types=1);

namespace Phpml\Classification\Linear;

use Phpml\Helper\Predictable;
use Phpml\Helper\OneVsRest;
use Phpml\Helper\Optimizer\StochasticGD;
use Phpml\Helper\Optimizer\GD;
use Phpml\Classification\Classifier;
use Phpml\Preprocessing\Normalizer;

class Perceptron implements Classifier
{
    use Predictable, OneVsRest;

   /**
     * @var array
     */
    protected $samples = [];

    /**
     * @var array
     */
    protected $targets = [];

    /**
     * @var array
     */
    protected $labels = [];

    /**
     * @var int
     */
    protected $featureCount = 0;

    /**
     * @var array
     */
    protected $weights;

    /**
     * @var float
     */
    protected $learningRate;

    /**
     * @var int
     */
    protected $maxIterations;

    /**
     * @var Normalizer
     */
    protected $normalizer;

    /**
     * @var bool
     */
    protected $enableEarlyStop = true;

    /**
     * @var array
     */
    protected $costValues = [];

    /**
     * Initalize a perceptron classifier with given learning rate and maximum
     * number of iterations used while training the perceptron <br>
     *
     * Learning rate should be a float value between 0.0(exclusive) and 1.0(inclusive) <br>
     * Maximum number of iterations can be an integer value greater than 0
     * @param int $learningRate
     * @param int $maxIterations
     */
    public function __construct(float $learningRate = 0.001, int $maxIterations = 1000,
        bool $normalizeInputs = true)
    {
        if ($learningRate <= 0.0 || $learningRate > 1.0) {
            throw new \Exception("Learning rate should be a float value between 0.0(exclusive) and 1.0(inclusive)");
        }

        if ($maxIterations <= 0) {
            throw new \Exception("Maximum number of iterations should be an integer greater than 0");
        }

        if ($normalizeInputs) {
            $this->normalizer = new Normalizer(Normalizer::NORM_STD);
        }

        $this->learningRate = $learningRate;
        $this->maxIterations = $maxIterations;
    }

   /**
     * @param array $samples
     * @param array $targets
     */
    public function trainBinary(array $samples, array $targets)
    {
        $this->labels = array_keys(array_count_values($targets));
        if (count($this->labels) > 2) {
            throw new \Exception("Perceptron is for binary (two-class) classification only");
        }

        if ($this->normalizer) {
            $this->normalizer->transform($samples);
        }

        // Set all target values to either -1 or 1
        $this->labels = [1 => $this->labels[0], -1 => $this->labels[1]];
        foreach ($targets as $target) {
            $this->targets[] = strval($target) == strval($this->labels[1]) ? 1 : -1;
        }

        // Set samples and feature count vars
        $this->samples = array_merge($this->samples, $samples);
        $this->featureCount = count($this->samples[0]);

        $this->runTraining();
    }

    /**
     * Normally enabling early stopping for the optimization procedure may
     * help saving processing time while in some cases it may result in
     * premature convergence.<br>
     *
     * If "false" is given, the optimization procedure will always be executed
     * for $maxIterations times
     *
     * @param bool $enable
     */
    public function setEarlyStop(bool $enable = true)
    {
        $this->enableEarlyStop = $enable;

        return $this;
    }

    /**
     * Returns the cost values obtained during the training.
     *
     * @return array
     */
    public function getCostValues()
    {
        return $this->costValues;
    }

    /**
     * Trains the perceptron model with Stochastic Gradient Descent optimization
     * to get the correct set of weights
     */
    protected function runTraining()
    {
        // The cost function is the sum of squares
        $callback = function ($weights, $sample, $target) {
            $this->weights = $weights;

            $prediction = $this->outputClass($sample);
            $gradient = $prediction - $target;
            $error = $gradient**2;

            return [$error, $gradient];
        };

        $this->runGradientDescent($callback);
    }

    /**
     * Executes Stochastic Gradient Descent algorithm for
     * the given cost function
     */
    protected function runGradientDescent(\Closure $gradientFunc, bool $isBatch = false)
    {
        $class = $isBatch ? GD::class :  StochasticGD::class;

        $optimizer = (new $class($this->featureCount))
            ->setLearningRate($this->learningRate)
            ->setMaxIterations($this->maxIterations)
            ->setChangeThreshold(1e-6)
            ->setEarlyStop($this->enableEarlyStop);

        $this->weights = $optimizer->runOptimization($this->samples, $this->targets, $gradientFunc);
        $this->costValues = $optimizer->getCostValues();
    }

    /**
     * Checks if the sample should be normalized and if so, returns the
     * normalized sample
     *
     * @param array $sample
     *
     * @return array
     */
    protected function checkNormalizedSample(array $sample)
    {
        if ($this->normalizer) {
            $samples = [$sample];
            $this->normalizer->transform($samples);
            $sample = $samples[0];
        }

        return $sample;
    }

    /**
     * Calculates net output of the network as a float value for the given input
     *
     * @param array $sample
     * @return int
     */
    protected function output(array $sample)
    {
        $sum = 0;
        foreach ($this->weights as $index => $w) {
            if ($index == 0) {
                $sum += $w;
            } else {
                $sum += $w * $sample[$index - 1];
            }
        }

        return $sum;
    }

    /**
     * Returns the class value (either -1 or 1) for the given input
     *
     * @param array $sample
     * @return int
     */
    protected function outputClass(array $sample)
    {
        return $this->output($sample) > 0 ? 1 : -1;
    }

    /**
     * Returns the probability of the sample of belonging to the given label.
     *
     * The probability is simply taken as the distance of the sample
     * to the decision plane.
     *
     * @param array $sample
     * @param mixed $label
     */
    protected function predictProbability(array $sample, $label)
    {
        $predicted = $this->predictSampleBinary($sample);

        if (strval($predicted) == strval($label)) {
            $sample = $this->checkNormalizedSample($sample);
            return abs($this->output($sample));
        }

        return 0.0;
    }

    /**
     * @param array $sample
     * @return mixed
     */
    protected function predictSampleBinary(array $sample)
    {
        $sample = $this->checkNormalizedSample($sample);

        $predictedClass = $this->outputClass($sample);

        return $this->labels[ $predictedClass ];
    }
}
