<?php

declare (strict_types = 1);

namespace tests\Phpml\Dataset\Demo;

use Phpml\Dataset\Demo\Iris;

class IrisTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadingIrisDataset()
    {
        $iris = new Iris();

        // whole dataset
        $this->assertEquals(150, count($iris->getSamples()));
        $this->assertEquals(150, count($iris->getTargets()));

        // one sample features count
        $this->assertEquals(4, count($iris->getSamples()[0]));
    }
}
