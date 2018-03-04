<?php

declare(strict_types=1);

namespace Phpml\Tests\Classification\Ensemble;

use Phpml\Classification\DecisionTree;
use Phpml\Classification\Ensemble\RandomForest;
use Phpml\Classification\NaiveBayes;
use Phpml\Exception\InvalidArgumentException;

class RandomForestTest extends BaggingTest
{
    public function testThrowExceptionWithInvalidClassifier(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('RandomForest can only use DecisionTree as base classifier');

        $classifier = new RandomForest();
        $classifier->setClassifer(NaiveBayes::class);
    }

    public function testThrowExceptionWithInvalidFeatureSubsetRatioType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Feature subset ratio must be a string or a float');

        $classifier = new RandomForest();
        $classifier->setFeatureSubsetRatio(1);
    }

    public function testThrowExceptionWithInvalidFeatureSubsetRatioFloat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('When a float is given, feature subset ratio should be between 0.1 and 1.0');

        $classifier = new RandomForest();
        $classifier->setFeatureSubsetRatio(1.1);
    }

    public function testThrowExceptionWithInvalidFeatureSubsetRatioString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("When a string is given, feature subset ratio can only be 'sqrt' or 'log'");

        $classifier = new RandomForest();
        $classifier->setFeatureSubsetRatio('pow');
    }

    protected function getClassifier($numBaseClassifiers = 50)
    {
        $classifier = new RandomForest($numBaseClassifiers);
        $classifier->setFeatureSubsetRatio('log');

        return $classifier;
    }

    protected function getAvailableBaseClassifiers()
    {
        return [DecisionTree::class => ['depth' => 5]];
    }
}
