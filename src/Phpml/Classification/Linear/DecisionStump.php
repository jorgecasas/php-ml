<?php

declare(strict_types=1);

namespace Phpml\Classification\Linear;

use Phpml\Helper\Predictable;
use Phpml\Helper\Trainable;
use Phpml\Classification\Classifier;
use Phpml\Classification\DecisionTree;

class DecisionStump extends DecisionTree
{
    use Trainable, Predictable;

    /**
     * @var int
     */
    protected $columnIndex;


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
        // Check if a column index was given
        if ($this->columnIndex >= 0 && $this->columnIndex > count($samples[0]) - 1) {
            $this->columnIndex = -1;
        }

        if ($this->columnIndex >= 0) {
            $this->setSelectedFeatures([$this->columnIndex]);
        }

        parent::train($samples, $targets);
    }
}
