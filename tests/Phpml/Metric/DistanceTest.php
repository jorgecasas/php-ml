<?php
declare(strict_types = 1);

namespace tests\Phpml\Metric;

use Phpml\Metric\Distance;

class DistanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Phpml\Exception\InvalidArgumentException
     */
    public function testThrowExceptionOnInvalidArguments()
    {
        Distance::euclidean([0, 1, 2], [0, 2]);
    }

}
