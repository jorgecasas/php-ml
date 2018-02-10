<?php

declare(strict_types=1);

namespace Phpml\Tests\FeatureSelection;

use Phpml\Exception\InvalidArgumentException;
use Phpml\FeatureSelection\VarianceThreshold;
use PHPUnit\Framework\TestCase;

final class VarianceThresholdTest extends TestCase
{
    public function testVarianceThreshold(): void
    {
        $samples = [[0, 0, 1], [0, 1, 0], [1, 0, 0], [0, 1, 1], [0, 1, 0], [0, 1, 1]];
        $transformer = new VarianceThreshold(0.8 * (1 - 0.8)); // 80% of samples - boolean features are Bernoulli random variables
        $transformer->fit($samples);
        $transformer->transform($samples);

        // expecting to remove first column
        self::assertEquals([[0, 1], [1, 0], [0, 0], [1, 1], [1, 0], [1, 1]], $samples);
    }

    public function testVarianceThresholdWithZeroThreshold(): void
    {
        $samples = [[0, 2, 0, 3], [0, 1, 4, 3], [0, 1, 1, 3]];
        $transformer = new VarianceThreshold();
        $transformer->fit($samples);
        $transformer->transform($samples);

        self::assertEquals([[2, 0], [1, 4], [1, 1]], $samples);
    }

    public function testThrowExceptionWhenThresholdBelowZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new VarianceThreshold(-0.1);
    }
}
