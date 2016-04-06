<?php

declare (strict_types = 1);

namespace tests\Phpml\Dataset;

use Phpml\Dataset\Iris;

class IrisTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadingIrisDataset()
    {
        $iris = new Iris();

        // whole dataset
        $this->assertEquals(150, count($iris->getSamples()));
        $this->assertEquals(150, count($iris->getLabels()));

        // one sample features count
        $this->assertEquals(4, count($iris->getSamples()[0]));
    }
}
