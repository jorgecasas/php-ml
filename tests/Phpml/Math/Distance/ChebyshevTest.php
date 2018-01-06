<?php

declare(strict_types=1);

namespace Phpml\Tests\Math\Distance;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Math\Distance\Chebyshev;
use PHPUnit\Framework\TestCase;

class ChebyshevTest extends TestCase
{
    /**
     * @var Chebyshev
     */
    private $distanceMetric;

    public function setUp(): void
    {
        $this->distanceMetric = new Chebyshev();
    }

    public function testThrowExceptionOnInvalidArguments(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $a = [0, 1, 2];
        $b = [0, 2];
        $this->distanceMetric->distance($a, $b);
    }

    public function testCalculateDistanceForOneDimension(): void
    {
        $a = [4];
        $b = [2];

        $expectedDistance = 2;
        $actualDistance = $this->distanceMetric->distance($a, $b);

        $this->assertEquals($expectedDistance, $actualDistance);
    }

    public function testCalculateDistanceForTwoDimensions(): void
    {
        $a = [4, 6];
        $b = [2, 5];

        $expectedDistance = 2;
        $actualDistance = $this->distanceMetric->distance($a, $b);

        $this->assertEquals($expectedDistance, $actualDistance);
    }

    public function testCalculateDistanceForThreeDimensions(): void
    {
        $a = [6, 10, 3];
        $b = [2, 5, 5];

        $expectedDistance = 5;
        $actualDistance = $this->distanceMetric->distance($a, $b);

        $this->assertEquals($expectedDistance, $actualDistance);
    }
}
