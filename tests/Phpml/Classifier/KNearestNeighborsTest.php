<?php

declare (strict_types = 1);

namespace tests\Classifier;

use Phpml\Classifier\KNearestNeighbors;

class KNearestNeighborsTest extends \PHPUnit_Framework_TestCase
{
    public function testPredictSimpleSampleWithDefaultK()
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
}
