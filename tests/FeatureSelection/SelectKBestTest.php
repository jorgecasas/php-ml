<?php

declare(strict_types=1);

namespace Phpml\Tests\FeatureSelection;

use Phpml\Dataset\Demo\IrisDataset;
use Phpml\Exception\InvalidArgumentException;
use Phpml\Exception\InvalidOperationException;
use Phpml\FeatureSelection\ScoringFunction\ANOVAFValue;
use Phpml\FeatureSelection\ScoringFunction\UnivariateLinearRegression;
use Phpml\FeatureSelection\SelectKBest;
use PHPUnit\Framework\TestCase;

final class SelectKBestTest extends TestCase
{
    public function testSelectKBestWithDefaultScoringFunction(): void
    {
        $samples = [[1, 2, 1], [1, 3, 4], [5, 2, 1], [1, 3, 3], [1, 3, 4], [0, 3, 5]];
        $targets = ['a', 'a', 'a', 'b', 'b', 'b'];
        $selector = new SelectKBest(2);
        $selector->fit($samples, $targets);
        $selector->transform($samples);

        self::assertEquals([[2, 1], [3, 4], [2, 1], [3, 3], [3, 4], [3, 5]], $samples);
    }

    public function testSelectKBestWithKBiggerThanFeatures(): void
    {
        $samples = [[1, 2, 1], [1, 3, 4], [5, 2, 1], [1, 3, 3], [1, 3, 4], [0, 3, 5]];
        $targets = ['a', 'a', 'a', 'b', 'b', 'b'];
        $selector = new SelectKBest(4);
        $selector->fit($samples, $targets);
        $selector->transform($samples);

        self::assertEquals([[1, 2, 1], [1, 3, 4], [5, 2, 1], [1, 3, 3], [1, 3, 4], [0, 3, 5]], $samples);
    }

    public function testSelectKBestWithIrisDataset(): void
    {
        $dataset = new IrisDataset();
        $selector = new SelectKBest(2, new ANOVAFValue());
        $selector->fit($samples = $dataset->getSamples(), $dataset->getTargets());
        $selector->transform($samples);

        self::assertEquals(2, count($samples[0]));
    }

    public function testSelectKBestWithRegressionScoring(): void
    {
        $samples = [[73676, 1996, 2], [77006, 1998, 5], [10565, 2000, 4], [146088, 1995, 2], [15000, 2001, 2], [65940, 2000, 2], [9300, 2000, 2], [93739, 1996, 2], [153260, 1994, 2], [17764, 2002, 2], [57000, 1998, 2], [15000, 2000, 2]];
        $targets = [2000, 2750, 15500, 960, 4400, 8800, 7100, 2550, 1025, 5900, 4600, 4400];

        $selector = new SelectKBest(2, new UnivariateLinearRegression());
        $selector->fit($samples, $targets);
        $selector->transform($samples);

        self::assertEquals(
            [[73676, 1996], [77006, 1998], [10565, 2000], [146088, 1995], [15000, 2001], [65940, 2000], [9300, 2000], [93739, 1996], [153260, 1994], [17764, 2002], [57000, 1998], [15000, 2000]],
            $samples
        );
    }

    public function testThrowExceptionOnEmptyTargets(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $selector = new SelectKBest(2, new ANOVAFValue());
        $selector->fit([[1, 2, 3], [4, 5, 6]], []);
    }

    public function testThrowExceptionWhenNotTrained(): void
    {
        $this->expectException(InvalidOperationException::class);
        $selector = new SelectKBest(2, new ANOVAFValue());
        $selector->scores();
    }
}
