<?php

declare(strict_types=1);

namespace tests\Classification;

use Phpml\Classification\NaiveBayes;

class NaiveBayesTest extends \PHPUnit_Framework_TestCase
{
    public function testPredictSingleSample()
    {
        $samples = [[5, 1, 1], [1, 5, 1], [1, 1, 5]];
        $labels = ['a', 'b', 'c'];

        $classifier = new NaiveBayes();
        $classifier->train($samples, $labels);

        $this->assertEquals('a', $classifier->predict([3, 1, 1]));
        $this->assertEquals('b', $classifier->predict([1, 4, 1]));
        $this->assertEquals('c', $classifier->predict([1, 1, 6]));
    }

    public function testPredictArrayOfSamples()
    {
        $trainSamples = [[5, 1, 1], [1, 5, 1], [1, 1, 5]];
        $trainLabels = ['a', 'b', 'c'];

        $testSamples = [[3, 1, 1], [5, 1, 1], [4, 3, 8], [1, 1, 2], [2, 3, 2], [1, 2, 1], [9, 5, 1], [3, 1, 2]];
        $testLabels = ['a', 'a', 'c', 'c', 'b', 'b', 'a', 'a'];

        $classifier = new NaiveBayes();
        $classifier->train($trainSamples, $trainLabels);
        $predicted = $classifier->predict($testSamples);

        $this->assertEquals($testLabels, $predicted);
    }
}
