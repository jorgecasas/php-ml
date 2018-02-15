<?php

declare(strict_types=1);

namespace Phpml\Tests\Math\Statistic;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Math\Statistic\ANOVA;
use PHPUnit\Framework\TestCase;

final class ANOVATest extends TestCase
{
    public function testOneWayF(): void
    {
        $samples = [
            [[1, 2, 1], [1, 3, 4], [5, 2, 1]],
            [[1, 3, 3], [1, 3, 4], [0, 3, 5]],
        ];

        $f = [1.47058824, 4.0, 3.0];

        self::assertEquals($f, ANOVA::oneWayF($samples), '', 0.00000001);
    }

    public function testOneWayFWithDifferingSizes(): void
    {
        $samples = [
            [[1, 2, 1], [1, 3, 4], [5, 2, 1]],
            [[1, 3, 3], [1, 3, 4]],
        ];

        self::assertEquals([0.6, 2.4, 1.24615385], ANOVA::oneWayF($samples), '', 0.00000001);
    }

    public function testThrowExceptionOnTooSmallSamples(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $samples = [
            [[1, 2, 1], [1, 3, 4], [5, 2, 1]],
        ];

        ANOVA::oneWayF($samples);
    }
}
