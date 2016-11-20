<?php

declare(strict_types=1);

namespace tests\Phpml\Dataset\Demo;

use Phpml\Dataset\Demo\WineDataset;

class WineDatasetTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadingWineDataset()
    {
        $wine = new WineDataset();

        // whole dataset
        $this->assertEquals(178, count($wine->getSamples()));
        $this->assertEquals(178, count($wine->getTargets()));

        // one sample features count
        $this->assertEquals(13, count($wine->getSamples()[0]));
    }
}
