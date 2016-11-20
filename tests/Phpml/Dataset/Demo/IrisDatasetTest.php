<?php

declare(strict_types=1);

namespace tests\Phpml\Dataset\Demo;

use Phpml\Dataset\Demo\IrisDataset;

class IrisDatasetTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadingIrisDataset()
    {
        $iris = new IrisDataset();

        // whole dataset
        $this->assertEquals(150, count($iris->getSamples()));
        $this->assertEquals(150, count($iris->getTargets()));

        // one sample features count
        $this->assertEquals(4, count($iris->getSamples()[0]));
    }
}
