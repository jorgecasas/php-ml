<?php

declare(strict_types=1);

namespace tests\Phpml\Dataset\Demo;

use Phpml\Dataset\Demo\GlassDataset;

class GlassDatasetTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadingWineDataset()
    {
        $glass = new GlassDataset();

        // whole dataset
        $this->assertEquals(214, count($glass->getSamples()));
        $this->assertEquals(214, count($glass->getTargets()));

        // one sample features count
        $this->assertEquals(9, count($glass->getSamples()[0]));
    }
}
