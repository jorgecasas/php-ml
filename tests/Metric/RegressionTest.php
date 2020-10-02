<?php

declare(strict_types=1);

namespace Phpml\Tests\Metric;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Metric\Regression;
use PHPUnit\Framework\TestCase;

final class RegressionTest extends TestCase
{
    public function testMeanSquaredError(): void
    {
        self::assertEquals(6.08, Regression::meanSquaredError(
            [41, 45, 49, 47, 44],
            [43.6, 44.4, 45.2, 46, 46.8]
        ));

        self::assertEquals(0.375, Regression::meanSquaredError(
            [3, -0.5, 2, 7],
            [2.5, 0.0, 2, 8]
        ));
    }

    public function testR2Score(): void
    {
        self::assertEqualsWithDelta(0.1739, Regression::r2Score(
            [41, 45, 49, 47, 44],
            [43.6, 44.4, 45.2, 46, 46.8]
        ), 0.0001);
    }

    public function testMaxError(): void
    {
        self::assertEquals(1, Regression::maxError([3, 2, 7, 1], [4, 2, 7, 1]));

        // test absolute value
        self::assertEquals(5, Regression::maxError([-10, 2, 7, 1], [-5, 2, 7, 1]));
    }

    public function testMeanAbsoluteError(): void
    {
        self::assertEquals(0.5, Regression::meanAbsoluteError([3, -0.5, 2, 7], [2.5, 0.0, 2, 8]));
    }

    public function testMeanSquaredLogarithmicError(): void
    {
        self::assertEqualsWithDelta(0.039, Regression::meanSquaredLogarithmicError(
            [3, 5, 2.5, 7],
            [2.5, 5, 4, 8]
        ), 0.001);
    }

    public function testMedianAbsoluteError(): void
    {
        self::assertEquals(0.5, Regression::medianAbsoluteError(
            [3, -0.5, 2, 7],
            [2.5, 0.0, 2, 8]
        ));
    }

    public function testMeanSquaredErrorInvalidCount(): void
    {
        self::expectException(InvalidArgumentException::class);

        Regression::meanSquaredError([1], [1, 2]);
    }

    public function testR2ScoreInvalidCount(): void
    {
        self::expectException(InvalidArgumentException::class);

        Regression::r2Score([1], [1, 2]);
    }

    public function testMaxErrorInvalidCount(): void
    {
        self::expectException(InvalidArgumentException::class);

        Regression::r2Score([1], [1, 2]);
    }

    public function tesMeanAbsoluteErrorInvalidCount(): void
    {
        self::expectException(InvalidArgumentException::class);

        Regression::meanAbsoluteError([1], [1, 2]);
    }

    public function tesMediaAbsoluteErrorInvalidCount(): void
    {
        self::expectException(InvalidArgumentException::class);

        Regression::medianAbsoluteError([1], [1, 2]);
    }
}
