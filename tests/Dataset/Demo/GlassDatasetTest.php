<?php

declare(strict_types=1);

namespace Phpml\Tests\Dataset\Demo;

use Phpml\Dataset\Demo\GlassDataset;
use PHPUnit\Framework\TestCase;

class GlassDatasetTest extends TestCase
{
    public function testLoadingWineDataset(): void
    {
        $glass = new GlassDataset();

        // whole dataset
        self::assertCount(214, $glass->getSamples());
        self::assertCount(214, $glass->getTargets());

        // one sample features count
        self::assertCount(9, $glass->getSamples()[0]);
    }
}
