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

    public function testSelectKBestIssue386(): void
    {
        $samples = [
            [
                0.0006729998475705993,
                0.0,
                0.999999773507577,
                0.0,
                0.0,
                6.66666515671718E-7,
                3.33333257835859E-6,
                6.66666515671718E-6,
            ],
            [
                0.0006729998475849566,
                0.0,
                0.9999997735289103,
                0.0,
                0.0,
                6.666665156859402E-7,
                3.3333325784297012E-6,
                1.3333330313718804E-6,
            ],
        ];

        $targets = [15.5844, 4.45284];

        $selector = new SelectKBest(2);
        $selector->fit($samples, $targets);

        self::assertEquals([
            -2.117582368135751E-22,
            0.0,
            0.0,
            0.0,
            0.0,
            1.0097419586828951E-28,
            0.0,
            1.4222215779620095E-11,
        ], $selector->scores());
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
