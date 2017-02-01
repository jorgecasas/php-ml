<?php

declare(strict_types=1);

namespace Phpml\Classification;

use Phpml\Helper\Predictable;
use Phpml\Helper\Trainable;
use Phpml\Math\Statistic\Mean;
use Phpml\Classification\DecisionTree\DecisionTreeLeaf;

class DecisionTree implements Classifier
{
    use Trainable, Predictable;

    const CONTINUOS = 1;
    const NOMINAL = 2;

    /**
     * @var array
     */
    private $samples = [];

    /**
     * @var array
     */
    private $columnTypes;

    /**
     * @var array
     */
    private $labels = [];

    /**
     * @var int
     */
    private $featureCount = 0;

    /**
     * @var DecisionTreeLeaf
     */
    private $tree = null;

    /**
     * @var int
     */
    private $maxDepth;

    /**
     * @var int
     */
    public $actualDepth = 0;

    /**
     * @param int $maxDepth
     */
    public function __construct($maxDepth = 10)
    {
        $this->maxDepth = $maxDepth;
    }
    /**
     * @param array $samples
     * @param array $targets
     */
    public function train(array $samples, array $targets)
    {
        $this->samples = array_merge($this->samples, $samples);
        $this->targets = array_merge($this->targets, $targets);

        $this->featureCount = count($this->samples[0]);
        $this->columnTypes = $this->getColumnTypes($this->samples);
        $this->labels = array_keys(array_count_values($this->targets));
        $this->tree = $this->getSplitLeaf(range(0, count($this->samples) - 1));
    }

    protected function getColumnTypes(array $samples)
    {
        $types = [];
        for ($i=0; $i<$this->featureCount; $i++) {
            $values = array_column($samples, $i);
            $isCategorical = $this->isCategoricalColumn($values);
            $types[] = $isCategorical ? self::NOMINAL : self::CONTINUOS;
        }
        return $types;
    }

    /**
     * @param null|array $records
     * @return DecisionTreeLeaf
     */
    protected function getSplitLeaf($records, $depth = 0)
    {
        $split = $this->getBestSplit($records);
        $split->level = $depth;
        if ($this->actualDepth < $depth) {
            $this->actualDepth = $depth;
        }
        $leftRecords = [];
        $rightRecords= [];
        $remainingTargets = [];
        $prevRecord = null;
        $allSame = true;
        foreach ($records as $recordNo) {
            $record = $this->samples[$recordNo];
            if ($prevRecord && $prevRecord != $record) {
                $allSame = false;
            }
            $prevRecord = $record;
            if ($split->evaluate($record)) {
                $leftRecords[] = $recordNo;
            } else {
                $rightRecords[]= $recordNo;
            }
            $target = $this->targets[$recordNo];
            if (! in_array($target, $remainingTargets)) {
                $remainingTargets[] = $target;
            }
        }

        if (count($remainingTargets) == 1 || $allSame || $depth >= $this->maxDepth) {
            $split->isTerminal = 1;
            $classes = array_count_values($remainingTargets);
            arsort($classes);
            $split->classValue = key($classes);
        } else {
            if ($leftRecords) {
                $split->leftLeaf = $this->getSplitLeaf($leftRecords, $depth + 1);
            }
            if ($rightRecords) {
                $split->rightLeaf= $this->getSplitLeaf($rightRecords, $depth + 1);
            }
        }
        return $split;
    }

    /**
     * @param array $records
     * @return DecisionTreeLeaf[]
     */
    protected function getBestSplit($records)
    {
        $targets = array_intersect_key($this->targets, array_flip($records));
        $samples = array_intersect_key($this->samples, array_flip($records));
        $samples = array_combine($records, $this->preprocess($samples));
        $bestGiniVal = 1;
        $bestSplit = null;
        for ($i=0; $i<$this->featureCount; $i++) {
            $colValues = [];
            $baseValue = null;
            foreach ($samples as $index => $row) {
                $colValues[$index] = $row[$i];
                if ($baseValue === null) {
                    $baseValue = $row[$i];
                }
            }
            $gini = $this->getGiniIndex($baseValue, $colValues, $targets);
            if ($bestSplit == null || $bestGiniVal > $gini) {
                $split = new DecisionTreeLeaf();
                $split->value = $baseValue;
                $split->giniIndex = $gini;
                $split->columnIndex = $i;
                $split->records = $records;
                $bestSplit = $split;
                $bestGiniVal = $gini;
            }
        }
        return $bestSplit;
    }

    /**
     * @param string $baseValue
     * @param array $colValues
     * @param array $targets
     */
    public function getGiniIndex($baseValue, $colValues, $targets)
    {
        $countMatrix = [];
        foreach ($this->labels as $label) {
            $countMatrix[$label] = [0, 0];
        }
        foreach ($colValues as $index => $value) {
            $label = $targets[$index];
            $rowIndex = $value == $baseValue ? 0 : 1;
            $countMatrix[$label][$rowIndex]++;
        }
        $giniParts = [0, 0];
        for ($i=0; $i<=1; $i++) {
            $part = 0;
            $sum = array_sum(array_column($countMatrix, $i));
            if ($sum > 0) {
                foreach ($this->labels as $label) {
                    $part += pow($countMatrix[$label][$i] / floatval($sum), 2);
                }
            }
            $giniParts[$i] = (1 - $part) * $sum;
        }
        return array_sum($giniParts) / count($colValues);
    }

    /**
     * @param array $samples
     * @return array
     */
    protected function preprocess(array $samples)
    {
        // Detect and convert continuous data column values into
        // discrete values by using the median as a threshold value
        $columns = [];
        for ($i=0; $i<$this->featureCount; $i++) {
            $values = array_column($samples, $i);
            if ($this->columnTypes[$i] == self::CONTINUOS) {
                $median = Mean::median($values);
                foreach ($values as &$value) {
                    if ($value <= $median) {
                        $value = "<= $median";
                    } else {
                        $value = "> $median";
                    }
                }
            }
            $columns[] = $values;
        }
        // Below method is a strange yet very simple & efficient method
        // to get the transpose of a 2D array
        return array_map(null, ...$columns);
    }

    /**
     * @param array $columnValues
     * @return bool
     */
    protected function isCategoricalColumn(array $columnValues)
    {
        $count = count($columnValues);
        // There are two main indicators that *may* show whether a
        // column is composed of discrete set of values:
        // 1- Column may contain string values
        // 2- Number of unique values in the column is only a small fraction of
        //	  all values in that column (Lower than or equal to %20 of all values)
        $numericValues = array_filter($columnValues, 'is_numeric');
        if (count($numericValues) != $count) {
            return true;
        }
        $distinctValues = array_count_values($columnValues);
        if (count($distinctValues) <= $count / 5) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->tree->__toString();
    }

    /**
     * @param array $sample
     * @return mixed
     */
    protected function predictSample(array $sample)
    {
        $node = $this->tree;
        do {
            if ($node->isTerminal) {
                break;
            }
            if ($node->evaluate($sample)) {
                $node = $node->leftLeaf;
            } else {
                $node = $node->rightLeaf;
            }
        } while ($node);
        return $node->classValue;
    }
}
