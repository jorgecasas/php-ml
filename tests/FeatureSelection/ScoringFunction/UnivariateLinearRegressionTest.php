<?php

declare(strict_types=1);

namespace Phpml\Tests\FeatureSelection\ScoringFunction;

use Phpml\FeatureSelection\ScoringFunction\UnivariateLinearRegression;
use PHPUnit\Framework\TestCase;

final class UnivariateLinearRegressionTest extends TestCase
{
    public function testRegressionScore(): void
    {
        $samples = [[73676, 1996], [77006, 1998], [10565, 2000], [146088, 1995], [15000, 2001], [65940, 2000], [9300, 2000], [93739, 1996], [153260, 1994], [17764, 2002], [57000, 1998], [15000, 2000]];
        $targets = [2000, 2750, 15500, 960, 4400, 8800, 7100, 2550, 1025, 5900, 4600, 4400];

        $function = new UnivariateLinearRegression();
        self::assertEquals([6.97286, 6.48558], $function->score($samples, $targets), '', 0.0001);
    }

    public function testRegressionScoreWithoutCenter(): void
    {
        $samples = [[73676, 1996], [77006, 1998], [10565, 2000], [146088, 1995], [15000, 2001], [65940, 2000], [9300, 2000], [93739, 1996], [153260, 1994], [17764, 2002], [57000, 1998], [15000, 2000]];
        $targets = [2000, 2750, 15500, 960, 4400, 8800, 7100, 2550, 1025, 5900, 4600, 4400];

        $function = new UnivariateLinearRegression(false);
        self::assertEquals([1.74450, 18.08347], $function->score($samples, $targets), '', 0.0001);
    }
}
