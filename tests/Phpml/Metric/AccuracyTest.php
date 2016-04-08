<?php
declare(strict_types = 1);

namespace tests\Phpml\Metric;

use Phpml\Metric\Accuracy;

class AccuracyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Phpml\Exception\InvalidArgumentException
     */
    public function testThrowExceptionOnInvalidArguments()
    {
        $actualLabels = ['a', 'b', 'a', 'b'];
        $predictedLabels = ['a', 'a'];

        Accuracy::score($actualLabels, $predictedLabels);
    }

    public function testCalculateNormalizedScore()
    {
        $actualLabels = ['a', 'b', 'a', 'b'];
        $predictedLabels = ['a', 'a', 'b', 'b'];

        $this->assertEquals(0.5, Accuracy::score($actualLabels, $predictedLabels));
    }

    public function testCalculateNotNormalizedScore()
    {
        $actualLabels = ['a', 'b', 'a', 'b'];
        $predictedLabels = ['a', 'b', 'b', 'b'];

        $this->assertEquals(3, Accuracy::score($actualLabels, $predictedLabels, false));
    }

}
