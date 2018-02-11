<?php

declare(strict_types=1);

namespace Phpml\Tests\FeatureSelection\ScoringFunction;

use Phpml\Dataset\Demo\IrisDataset;
use Phpml\FeatureSelection\ScoringFunction\ANOVAFValue;
use PHPUnit\Framework\TestCase;

final class ANOVAFValueTest extends TestCase
{
    public function testScoreForANOVAFValue(): void
    {
        $dataset = new IrisDataset();
        $function = new ANOVAFValue();

        self::assertEquals(
            [119.2645, 47.3644, 1179.0343, 959.3244],
            $function->score($dataset->getSamples(), $dataset->getTargets()),
            '',
            0.0001
        );
    }
}
