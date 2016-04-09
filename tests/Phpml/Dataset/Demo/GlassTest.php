<?php

declare (strict_types = 1);

namespace tests\Phpml\Dataset\Demo;

use Phpml\Dataset\Demo\Glass;

class GlassTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadingWineDataset()
    {
        $iris = new Glass();

        // whole dataset
        $this->assertEquals(214, count($iris->getSamples()));
        $this->assertEquals(214, count($iris->getLabels()));

        // one sample features count
        $this->assertEquals(9, count($iris->getSamples()[0]));
    }
}
