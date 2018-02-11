<?php

declare(strict_types=1);

namespace Phpml\Tests\FeatureSelection;

use Phpml\Dataset\Demo\IrisDataset;
use Phpml\Exception\InvalidArgumentException;
use Phpml\Exception\InvalidOperationException;
use Phpml\FeatureSelection\ScoringFunction\ANOVAFValue;
use Phpml\FeatureSelection\SelectKBest;
use PHPUnit\Framework\TestCase;

final class SelectKBestTest extends TestCase
{
    public function testSelectKBestWithDefaultScoringFunction(): void
    {
        $samples = [[1, 2, 1], [1, 3, 4], [5, 2, 1], [1, 3, 3], [1, 3, 4], [0, 3, 5]];
        $targets = ['a', 'a', 'a', 'b', 'b', 'b'];
        $selector = new SelectKBest(null, 2);
        $selector->fit($samples, $targets);
        $selector->transform($samples);

        self::assertEquals([[2, 1], [3, 4], [2, 1], [3, 3], [3, 4], [3, 5]], $samples);
    }

    public function testSelectKBestWithKBiggerThanFeatures(): void
    {
        $samples = [[1, 2, 1], [1, 3, 4], [5, 2, 1], [1, 3, 3], [1, 3, 4], [0, 3, 5]];
        $targets = ['a', 'a', 'a', 'b', 'b', 'b'];
        $selector = new SelectKBest(null, 4);
        $selector->fit($samples, $targets);
        $selector->transform($samples);

        self::assertEquals([[1, 2, 1], [1, 3, 4], [5, 2, 1], [1, 3, 3], [1, 3, 4], [0, 3, 5]], $samples);
    }

    public function testSelectKBestWithIrisDataset(): void
    {
        $dataset = new IrisDataset();
        $selector = new SelectKBest(new ANOVAFValue(), 2);
        $selector->fit($samples = $dataset->getSamples(), $dataset->getTargets());
        $selector->transform($samples);

        self::assertEquals(2, count($samples[0]));
    }

    public function testThrowExceptionOnEmptyTargets(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $selector = new SelectKBest(new ANOVAFValue(), 2);
        $selector->fit([[1, 2, 3], [4, 5, 6]], []);
    }

    public function testThrowExceptionWhenNotTrained(): void
    {
        $this->expectException(InvalidOperationException::class);
        $selector = new SelectKBest(new ANOVAFValue(), 2);
        $selector->scores();
    }
}
