<?php

declare(strict_types=1);

namespace Phpml\Tests\Dataset\Demo;

use Phpml\Dataset\Demo\IrisDataset;
use PHPUnit\Framework\TestCase;

class IrisDatasetTest extends TestCase
{
    public function testLoadingIrisDataset(): void
    {
        $iris = new IrisDataset();

        // whole dataset
        self::assertCount(150, $iris->getSamples());
        self::assertCount(150, $iris->getTargets());

        // one sample features count
        self::assertCount(4, $iris->getSamples()[0]);
    }
}
