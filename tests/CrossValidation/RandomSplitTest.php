<?php

declare(strict_types=1);

namespace Phpml\Tests\CrossValidation;

use Phpml\CrossValidation\RandomSplit;
use Phpml\Dataset\ArrayDataset;
use Phpml\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RandomSplitTest extends TestCase
{
    public function testThrowExceptionOnTooSmallTestSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RandomSplit(new ArrayDataset([], []), 0);
    }

    public function testThrowExceptionOnToBigTestSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RandomSplit(new ArrayDataset([], []), 1);
    }

    public function testDatasetRandomSplitWithoutSeed(): void
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4]],
            $labels = ['a', 'a', 'b', 'b']
        );

        $randomSplit = new RandomSplit($dataset, 0.5);

        self::assertCount(2, $randomSplit->getTestSamples());
        self::assertCount(2, $randomSplit->getTrainSamples());

        $randomSplit2 = new RandomSplit($dataset, 0.25);

        self::assertCount(1, $randomSplit2->getTestSamples());
        self::assertCount(3, $randomSplit2->getTrainSamples());
    }

    public function testDatasetRandomSplitWithSameSeed(): void
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4], [5], [6], [7], [8]],
            $labels = ['a', 'a', 'a', 'a', 'b', 'b', 'b', 'b']
        );

        $seed = 123;

        $randomSplit1 = new RandomSplit($dataset, 0.5, $seed);
        $randomSplit2 = new RandomSplit($dataset, 0.5, $seed);

        self::assertEquals($randomSplit1->getTestLabels(), $randomSplit2->getTestLabels());
        self::assertEquals($randomSplit1->getTestSamples(), $randomSplit2->getTestSamples());
        self::assertEquals($randomSplit1->getTrainLabels(), $randomSplit2->getTrainLabels());
        self::assertEquals($randomSplit1->getTrainSamples(), $randomSplit2->getTrainSamples());
    }

    public function testDatasetRandomSplitWithDifferentSeed(): void
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4], [5], [6], [7], [8]],
            $labels = ['a', 'a', 'a', 'a', 'b', 'b', 'b', 'b']
        );

        $randomSplit1 = new RandomSplit($dataset, 0.5, 4321);
        $randomSplit2 = new RandomSplit($dataset, 0.5, 1234);

        self::assertNotEquals($randomSplit1->getTestLabels(), $randomSplit2->getTestLabels());
        self::assertNotEquals($randomSplit1->getTestSamples(), $randomSplit2->getTestSamples());
        self::assertNotEquals($randomSplit1->getTrainLabels(), $randomSplit2->getTrainLabels());
        self::assertNotEquals($randomSplit1->getTrainSamples(), $randomSplit2->getTrainSamples());
    }

    public function testRandomSplitCorrectSampleAndLabelPosition(): void
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4]],
            $labels = [1, 2, 3, 4]
        );

        $randomSplit = new RandomSplit($dataset, 0.5);

        self::assertEquals($randomSplit->getTestSamples()[0][0], $randomSplit->getTestLabels()[0]);
        self::assertEquals($randomSplit->getTestSamples()[1][0], $randomSplit->getTestLabels()[1]);
        self::assertEquals($randomSplit->getTrainSamples()[0][0], $randomSplit->getTrainLabels()[0]);
        self::assertEquals($randomSplit->getTrainSamples()[1][0], $randomSplit->getTrainLabels()[1]);
    }
}
