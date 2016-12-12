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
        $this->assertCount(214, $glass->getSamples());
        $this->assertCount(214, $glass->getTargets());

        // one sample features count
        $this->assertCount(9, $glass->getSamples()[0]);
    }
}
