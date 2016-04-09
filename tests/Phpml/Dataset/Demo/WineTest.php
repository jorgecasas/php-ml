<?php

declare (strict_types = 1);

namespace tests\Phpml\Dataset\Demo;

use Phpml\Dataset\Demo\Wine;

class WineTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadingWineDataset()
    {
        $iris = new Wine();

        // whole dataset
        $this->assertEquals(178, count($iris->getSamples()));
        $this->assertEquals(178, count($iris->getLabels()));

        // one sample features count
        $this->assertEquals(13, count($iris->getSamples()[0]));
    }
}
