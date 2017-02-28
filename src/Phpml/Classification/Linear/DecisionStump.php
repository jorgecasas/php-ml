<?php

declare(strict_types=1);

namespace Phpml\Classification\Linear;

use Phpml\Helper\Predictable;
use Phpml\Helper\Trainable;
use Phpml\Classification\WeightedClassifier;
use Phpml\Classification\DecisionTree;

class DecisionStump extends WeightedClassifier
{
    use Trainable, Predictable;

    const AUTO_SELECT = -1;

    /**
     * @var int
     */
    protected $givenColumnIndex;


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
     * @var int
     */
    protected $column;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var array
     */
    protected $columnTypes;

    /**
     * @var float
     */
    protected $numSplitCount = 10.0;

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
    public function __construct(int $columnIndex = self::AUTO_SELECT)
    {
        $this->givenColumnIndex = $columnIndex;
    }

    /**
     * @param array $samples
     * @param array $targets
     */
    public function train(array $samples, array $targets)
    {
        $this->samples = array_merge($this->samples, $samples);
        $this->targets = array_merge($this->targets, $targets);

        // DecisionStump is capable of classifying between two classes only
        $labels = array_count_values($this->targets);
        $this->labels = array_keys($labels);
        if (count($this->labels) != 2) {
            throw new \Exception("DecisionStump can classify between two classes only:" . implode(',', $this->labels));
        }

        // If a column index is given, it should be among the existing columns
        if ($this->givenColumnIndex > count($samples[0]) - 1) {
            $this->givenColumnIndex = self::AUTO_SELECT;
        }

        // Check the size of the weights given.
        // If none given, then assign 1 as a weight to each sample
        if ($this->weights) {
            $numWeights = count($this->weights);
            if ($numWeights != count($this->samples)) {
                throw new \Exception("Number of sample weights does not match with number of samples");
            }
        } else {
            $this->weights = array_fill(0, count($samples), 1);
        }

        // Determine type of each column as either "continuous" or "nominal"
        $this->columnTypes = DecisionTree::getColumnTypes($this->samples);

        // Try to find the best split in the columns of the dataset
        // by calculating error rate for each split point in each column
        $columns = range(0, count($samples[0]) - 1);
        if ($this->givenColumnIndex != self::AUTO_SELECT) {
            $columns = [$this->givenColumnIndex];
        }

        $bestSplit = [
            'value' => 0, 'operator' => '',
            'column' => 0, 'trainingErrorRate' => 1.0];
        foreach ($columns as $col) {
            if ($this->columnTypes[$col] == DecisionTree::CONTINUOS) {
                $split = $this->getBestNumericalSplit($col);
            } else {
                $split = $this->getBestNominalSplit($col);
            }

            if ($split['trainingErrorRate'] < $bestSplit['trainingErrorRate']) {
                $bestSplit = $split;
            }
        }

        // Assign determined best values to the stump
        foreach ($bestSplit as $name => $value) {
            $this->{$name} = $value;
        }
    }

    /**
     * While finding best split point for a numerical valued column,
     * DecisionStump looks for equally distanced values between minimum and maximum
     * values in the column. Given <i>$count</i> value determines how many split
     * points to be probed. The more split counts, the better performance but
     * worse processing time (Default value is 10.0)
     *
     * @param float $count
     */
    public function setNumericalSplitCount(float $count)
    {
        $this->numSplitCount = $count;
    }

    /**
     * Determines best split point for the given column
     *
     * @param int $col
     *
     * @return array
     */
    protected function getBestNumericalSplit(int $col)
    {
        $values = array_column($this->samples, $col);
        $minValue = min($values);
        $maxValue = max($values);
        $stepSize = ($maxValue - $minValue) / $this->numSplitCount;

        $split = null;

        foreach (['<=', '>'] as $operator) {
            // Before trying all possible split points, let's first try
            // the average value for the cut point
            $threshold = array_sum($values) / (float) count($values);
            $errorRate = $this->calculateErrorRate($threshold, $operator, $values);
            if ($split == null || $errorRate < $split['trainingErrorRate']) {
                $split = ['value' => $threshold, 'operator' => $operator,
                        'column' => $col, 'trainingErrorRate' => $errorRate];
            }

            // Try other possible points one by one
            for ($step = $minValue; $step <= $maxValue; $step+= $stepSize) {
                $threshold = (float)$step;
                $errorRate = $this->calculateErrorRate($threshold, $operator, $values);
                if ($errorRate < $split['trainingErrorRate']) {
                    $split = ['value' => $threshold, 'operator' => $operator,
                        'column' => $col, 'trainingErrorRate' => $errorRate];
                }
            }// for
        }

        return $split;
    }

    /**
     *
     * @param int $col
     *
     * @return array
     */
    protected function getBestNominalSplit(int $col)
    {
        $values = array_column($this->samples, $col);
        $valueCounts = array_count_values($values);
        $distinctVals= array_keys($valueCounts);

        $split = null;

        foreach (['=', '!='] as $operator) {
            foreach ($distinctVals as $val) {
                $errorRate = $this->calculateErrorRate($val, $operator, $values);

                if ($split == null || $split['trainingErrorRate'] < $errorRate) {
                    $split = ['value' => $val, 'operator' => $operator,
                        'column' => $col, 'trainingErrorRate' => $errorRate];
                }
            }// for
        }

        return $split;
    }


    /**
     *
     * @param type $leftValue
     * @param type $operator
     * @param type $rightValue
     *
     * @return boolean
     */
    protected function evaluate($leftValue, $operator, $rightValue)
    {
        switch ($operator) {
            case '>': return $leftValue > $rightValue;
            case '>=': return $leftValue >= $rightValue;
            case '<': return $leftValue < $rightValue;
            case '<=': return $leftValue <= $rightValue;
            case '=': return $leftValue == $rightValue;
            case '!=':
            case '<>': return $leftValue != $rightValue;
        }

        return false;
    }

    /**
     * Calculates the ratio of wrong predictions based on the new threshold
     * value given as the parameter
     *
     * @param float $threshold
     * @param string $operator
     * @param array $values
     */
    protected function calculateErrorRate(float $threshold, string $operator, array $values)
    {
        $total = (float) array_sum($this->weights);
        $wrong = 0.0;
        $leftLabel = $this->labels[0];
        $rightLabel= $this->labels[1];
        foreach ($values as $index => $value) {
            if ($this->evaluate($threshold, $operator, $value)) {
                $predicted = $leftLabel;
            } else {
                $predicted = $rightLabel;
            }

            if ($predicted != $this->targets[$index]) {
                $wrong += $this->weights[$index];
            }
        }

        return $wrong / $total;
    }

    /**
     * @param array $sample
     * @return mixed
     */
    protected function predictSample(array $sample)
    {
        if ($this->evaluate($this->value, $this->operator, $sample[$this->column])) {
            return $this->labels[0];
        }
        return $this->labels[1];
    }

    public function __toString()
    {
        return "$this->column $this->operator $this->value";
    }
}
