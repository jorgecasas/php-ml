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
        $this->assertCount(150, $iris->getSamples());
        $this->assertCount(150, $iris->getTargets());

        // one sample features count
        $this->assertCount(4, $iris->getSamples()[0]);
    }
}
