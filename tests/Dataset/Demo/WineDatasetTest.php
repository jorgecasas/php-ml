<?php

declare(strict_types=1);

namespace Phpml\Tests\Dataset\Demo;

use Phpml\Dataset\Demo\WineDataset;
use PHPUnit\Framework\TestCase;

class WineDatasetTest extends TestCase
{
    public function testLoadingWineDataset(): void
    {
        $wine = new WineDataset();

        // whole dataset
        self::assertCount(178, $wine->getSamples());
        self::assertCount(178, $wine->getTargets());

        // one sample features count
        self::assertCount(13, $wine->getSamples()[0]);
    }
}
