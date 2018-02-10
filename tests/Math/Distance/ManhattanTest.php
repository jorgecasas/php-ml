<?php

declare(strict_types=1);

namespace Phpml\Tests\Math\Distance;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Math\Distance\Manhattan;
use PHPUnit\Framework\TestCase;

class ManhattanTest extends TestCase
{
    /**
     * @var Manhattan
     */
    private $distanceMetric;

    public function setUp(): void
    {
        $this->distanceMetric = new Manhattan();
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

        $expectedDistance = 3;
        $actualDistance = $this->distanceMetric->distance($a, $b);

        $this->assertEquals($expectedDistance, $actualDistance);
    }

    public function testCalculateDistanceForThreeDimensions(): void
    {
        $a = [6, 10, 3];
        $b = [2, 5, 5];

        $expectedDistance = 11;
        $actualDistance = $this->distanceMetric->distance($a, $b);

        $this->assertEquals($expectedDistance, $actualDistance);
    }
}
