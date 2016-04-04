<?php

declare (strict_types = 1);

namespace tests\Phpml\Metric;

use Phpml\Metric\Distance;

class DistanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Phpml\Exception\InvalidArgumentException
     */
    public function testThrowExceptionOnInvalidArgumentsInEuclidean()
    {
        $a = [0, 1, 2];
        $b = [0, 2];

        Distance::euclidean($a, $b);
    }

    public function testCalculateEuclideanDistanceForOneDimension()
    {
        $a = [4];
        $b = [2];

        $expectedDistance = 2;
        $actualDistance = Distance::euclidean($a, $b);

        \PHPUnit_Framework_Assert::assertEquals($expectedDistance, $actualDistance);
    }

    public function testCalculateEuclideanDistanceForTwoAndMoreDimension()
    {
        $a = [4, 6];
        $b = [2, 5];

        $expectedDistance = 2.2360679774998;
        $actualDistance = Distance::euclidean($a, $b);

        \PHPUnit_Framework_Assert::assertEquals($expectedDistance, $actualDistance);

        $a = [6, 10, 3];
        $b = [2, 5, 5];

        $expectedDistance = 6.7082039324993694;
        $actualDistance = Distance::euclidean($a, $b);

        \PHPUnit_Framework_Assert::assertEquals($expectedDistance, $actualDistance);
    }
}
