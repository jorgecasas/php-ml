<?php

declare(strict_types=1);

namespace Phpml\Tests\Dataset;

use Phpml\Dataset\MnistDataset;
use Phpml\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MnistDatasetTest extends TestCase
{
    public function testSimpleMnistDataset(): void
    {
        $dataset = new MnistDataset(
            __DIR__.'/Resources/mnist/images-idx-ubyte',
            __DIR__.'/Resources/mnist/labels-idx-ubyte'
        );

        self::assertCount(10, $dataset->getSamples());
        self::assertCount(10, $dataset->getTargets());
    }

    public function testCheckSamplesAndTargetsCountMatch(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new MnistDataset(
            __DIR__.'/Resources/mnist/images-idx-ubyte',
            __DIR__.'/Resources/mnist/labels-11-idx-ubyte'
        );
    }
}
