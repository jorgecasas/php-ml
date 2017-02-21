<?php

declare(strict_types=1);

namespace Phpml\Classification\Linear;

use Phpml\Helper\Predictable;
use Phpml\Helper\Trainable;
use Phpml\Classification\Classifier;
use Phpml\Classification\DecisionTree;
use Phpml\Classification\DecisionTree\DecisionTreeLeaf;

class DecisionStump extends DecisionTree
{
    use Trainable, Predictable;

    /**
     * @var int
     */
    protected $columnIndex;


    /**
     * Sample weights : If used the optimization on the decision value
     * will take these weights into account. If not given, all samples
     * will be weighed with the same value of 1
     *
     * @var array
     */
    protected $weights = null;

    /**
     * Lowest error rate obtained while training/optimizing the model
     *
     * @var float
     */
    protected $trainingErrorRate;

    /**
     * A DecisionStump classifier is a one-level deep DecisionTree. It is generally
     * used with ensemble algorithms as in the weak classifier role. <br>
     *
     * If columnIndex is given, then the stump tries to produce a decision node
     * on this column, otherwise in cases given the value of -1, the stump itself
     * decides which column to take for the decision (Default DecisionTree behaviour)
     *
     * @param int $columnIndex
     */
    public function __construct(int $columnIndex = -1)
    {
        $this->columnIndex = $columnIndex;

        parent::__construct(1);
    }

    /**
     * @param array $samples
     * @param array $targets
     */
    public function train(array $samples, array $targets)
    {
        if ($this->columnIndex > count($samples[0]) - 1) {
            $this->columnIndex = -1;
        }

        if ($this->columnIndex >= 0) {
            $this->setSelectedFeatures([$this->columnIndex]);
        }

        if ($this->weights) {
            $numWeights = count($this->weights);
            if ($numWeights != count($samples)) {
                throw new \Exception("Number of sample weights does not match with number of samples");
            }
        } else {
            $this->weights = array_fill(0, count($samples), 1);
        }

        parent::train($samples, $targets);

        $this->columnIndex = $this->tree->columnIndex;

        // For numerical values, try to optimize the value by finding a different threshold value
        if ($this->columnTypes[$this->columnIndex] == self::CONTINUOS) {
            $this->optimizeDecision($samples, $targets);
        }
    }

    /**
     * Used to set sample weights.
     *
     * @param array $weights
     */
    public function setSampleWeights(array $weights)
    {
        $this->weights = $weights;
    }

    /**
     * Returns the training error rate, the proportion of wrong predictions
     * over the total number of samples
     *
     * @return float
     */
    public function getTrainingErrorRate()
    {
        return $this->trainingErrorRate;
    }

    /**
     * Tries to optimize the threshold by probing a range of different values
     * between the minimum and maximum values in the selected column
     *
     * @param array $samples
     * @param array $targets
     */
    protected function optimizeDecision(array $samples, array $targets)
    {
        $values = array_column($samples, $this->columnIndex);
        $minValue = min($values);
        $maxValue = max($values);
        $stepSize = ($maxValue - $minValue) / 100.0;

        $leftLabel = $this->tree->leftLeaf->classValue;
        $rightLabel= $this->tree->rightLeaf->classValue;

        $bestOperator = $this->tree->operator;
        $bestThreshold = $this->tree->numericValue;
        $bestErrorRate = $this->calculateErrorRate(
                $bestThreshold, $bestOperator, $values, $targets, $leftLabel, $rightLabel);

        foreach (['<=', '>'] as $operator) {
            for ($step = $minValue; $step <= $maxValue; $step+= $stepSize) {
                $threshold = (float)$step;
                $errorRate = $this->calculateErrorRate(
                    $threshold, $operator, $values, $targets, $leftLabel, $rightLabel);

                if ($errorRate < $bestErrorRate) {
                    $bestErrorRate = $errorRate;
                    $bestThreshold = $threshold;
                    $bestOperator = $operator;
                }
            }// for
        }

        // Update the tree node value
        $this->tree->numericValue = $bestThreshold;
        $this->tree->operator = $bestOperator;
        $this->tree->value = "$bestOperator $bestThreshold";
        $this->trainingErrorRate = $bestErrorRate;
    }

    /**
     * Calculates the ratio of wrong predictions based on the new threshold
     * value given as the parameter
     *
     * @param float $threshold
     * @param string $operator
     * @param array $values
     * @param array $targets
     * @param mixed $leftLabel
     * @param mixed $rightLabel
     */
    protected function calculateErrorRate(float $threshold, string $operator, array $values, array $targets, $leftLabel, $rightLabel)
    {
        $total = (float) array_sum($this->weights);
        $wrong = 0;

        foreach ($values as $index => $value) {
            eval("\$predicted = \$value $operator \$threshold ? \$leftLabel : \$rightLabel;");

            if ($predicted != $targets[$index]) {
                $wrong += $this->weights[$index];
            }
        }

        return $wrong / $total;
    }
}
