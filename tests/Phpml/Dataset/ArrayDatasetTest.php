<?php

declare(strict_types=1);

namespace Phpml\Tests\Dataset;

use Phpml\Dataset\ArrayDataset;
use Phpml\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ArrayDatasetTest extends TestCase
{
    public function testThrowExceptionOnInvalidArgumentsSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ArrayDataset([0, 1], [0]);
    }

    public function testArrayDataset(): void
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4]],
            $labels = ['a', 'a', 'b', 'b']
        );

        $this->assertEquals($samples, $dataset->getSamples());
        $this->assertEquals($labels, $dataset->getTargets());
    }
}
