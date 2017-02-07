<?php

declare(strict_types=1);

namespace Phpml\Classification\Ensemble;

use Phpml\Classification\Ensemble\Bagging;
use Phpml\Classification\DecisionTree;
use Phpml\Classification\NaiveBayes;
use Phpml\Classification\Classifier;

class RandomForest extends Bagging
{
    /**
     * @var float|string
     */
    protected $featureSubsetRatio = 'log';

    public function __construct($numClassifier = 50)
    {
        parent::__construct($numClassifier);

        $this->setSubsetRatio(1.0);
    }

    /**
     * This method is used to determine how much of the original columns (features)
     * will be used to construct subsets to train base classifiers.<br>
     *
     * Allowed values: 'sqrt', 'log' or any float number between 0.1 and 1.0 <br>
     *
     * If there are many features that diminishes classification performance, then
     * small values should be preferred, otherwise, with low number of features,
     * default value (0.7) will result in satisfactory performance.
     *
     * @param mixed $ratio string or float should be given
     * @return $this
     * @throws Exception
     */
    public function setFeatureSubsetRatio($ratio)
    {
        if (is_float($ratio) && ($ratio < 0.1 || $ratio > 1.0)) {
            throw new \Exception("When a float given, feature subset ratio should be between 0.1 and 1.0");
        }
        if (is_string($ratio) && $ratio != 'sqrt' && $ratio != 'log') {
            throw new \Exception("When a string given, feature subset ratio can only be 'sqrt' or 'log' ");
        }
        $this->featureSubsetRatio = $ratio;
        return $this;
    }

    /**
     * RandomForest algorithm is usable *only* with DecisionTree
     *
     * @param string $classifier
     * @param array $classifierOptions
     * @return $this
     */
    public function setClassifer(string $classifier, array $classifierOptions = [])
    {
        if ($classifier != DecisionTree::class) {
            throw new \Exception("RandomForest can only use DecisionTree as base classifier");
        }

        return parent::setClassifer($classifier, $classifierOptions);
    }

    /**
     * @param DecisionTree $classifier
     * @param int $index
     * @return DecisionTree
     */
    protected function initSingleClassifier($classifier, $index)
    {
        if (is_float($this->featureSubsetRatio)) {
            $featureCount = (int)($this->featureSubsetRatio * $this->featureCount);
        } elseif ($this->featureCount == 'sqrt') {
            $featureCount = (int)sqrt($this->featureCount) + 1;
        } else {
            $featureCount = (int)log($this->featureCount, 2) + 1;
        }

        if ($featureCount >= $this->featureCount) {
            $featureCount = $this->featureCount;
        }

        return $classifier->setNumFeatures($featureCount);
    }
}
