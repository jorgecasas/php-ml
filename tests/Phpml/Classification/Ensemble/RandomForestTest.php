<?php

declare(strict_types=1);

namespace Phpml\Tests\Classification\Ensemble;

use Phpml\Classification\DecisionTree;
use Phpml\Classification\Ensemble\RandomForest;
use Phpml\Classification\NaiveBayes;
use Throwable;

class RandomForestTest extends BaggingTest
{
    public function testOtherBaseClassifier(): void
    {
        try {
            $classifier = new RandomForest();
            $classifier->setClassifer(NaiveBayes::class);
            $this->assertEquals(0, 1);
        } catch (Throwable $ex) {
            $this->assertEquals(1, 1);
        }
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
