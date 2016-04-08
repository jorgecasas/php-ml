<?php

declare (strict_types = 1);

namespace tests\Classifier;

use Phpml\Classifier\KNearestNeighbors;
use Phpml\CrossValidation\RandomSplit;
use Phpml\Dataset\Demo\Iris;
use Phpml\Metric\Accuracy;

class KNearestNeighborsTest extends \PHPUnit_Framework_TestCase
{
    public function testPredictSingleSampleWithDefaultK()
    {
        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $classifier = new KNearestNeighbors();
        $classifier->train($samples, $labels);

        $this->assertEquals('b', $classifier->predict([3, 2]));
        $this->assertEquals('b', $classifier->predict([5, 1]));
        $this->assertEquals('b', $classifier->predict([4, 3]));
        $this->assertEquals('b', $classifier->predict([4, -5]));

        $this->assertEquals('a', $classifier->predict([2, 3]));
        $this->assertEquals('a', $classifier->predict([1, 2]));
        $this->assertEquals('a', $classifier->predict([1, 5]));
        $this->assertEquals('a', $classifier->predict([3, 10]));
    }

    public function testPredictArrayOfSamples()
    {
        $trainSamples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $trainLabels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $testSamples = [[3, 2], [5, 1], [4, 3], [4, -5], [2, 3], [1, 2], [1, 5], [3, 10]];
        $testLabels = ['b', 'b', 'b', 'b', 'a', 'a', 'a', 'a'];

        $classifier = new KNearestNeighbors();
        $classifier->train($trainSamples, $trainLabels);
        $predicted = $classifier->predict($testSamples);

        $this->assertEquals($testLabels, $predicted);
    }

    public function testAccuracyOnIrisDataset()
    {
        $dataset = new RandomSplit(new Iris(), $testSize = 0.5, $seed = 123);
        $classifier = new KNearestNeighbors($k = 4);
        $classifier->train($dataset->getTrainSamples(), $dataset->getTrainLabels());
        $predicted = $classifier->predict($dataset->getTestSamples());
        $score = Accuracy::score($dataset->getTestLabels(), $predicted);

        $this->assertEquals(0.96, $score);
    }
}
