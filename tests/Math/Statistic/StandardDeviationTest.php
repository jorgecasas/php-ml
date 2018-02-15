<?php

declare(strict_types=1);

namespace Phpml\Tests\Math\Statistic;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Math\Statistic\StandardDeviation;
use PHPUnit\Framework\TestCase;

class StandardDeviationTest extends TestCase
{
    public function testStandardDeviationOfPopulationSample(): void
    {
        //https://pl.wikipedia.org/wiki/Odchylenie_standardowe
        $delta = 0.001;
        $population = [5, 6, 8, 9];
        $this->assertEquals(1.825, StandardDeviation::population($population), '', $delta);

        //http://www.stat.wmich.edu/s216/book/node126.html
        $delta = 0.5;
        $population = [7100, 15500, 4400, 4400, 5900, 4600, 8800, 2000, 2750, 2550,  960, 1025];
        $this->assertEquals(4079, StandardDeviation::population($population), '', $delta);

        $population = [9300,  10565,  15000,  15000,  17764,  57000,  65940,  73676,  77006,  93739, 146088, 153260];
        $this->assertEquals(50989, StandardDeviation::population($population), '', $delta);
    }

    public function testThrowExceptionOnEmptyArrayIfNotSample(): void
    {
        $this->expectException(InvalidArgumentException::class);
        StandardDeviation::population([], false);
    }

    public function testThrowExceptionOnTooSmallArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        StandardDeviation::population([1]);
    }

    /**
     * @dataProvider dataProviderForSumOfSquaresDeviations
     */
    public function testSumOfSquares(array $numbers, float $sum): void
    {
        self::assertEquals($sum, StandardDeviation::sumOfSquares($numbers), '', 0.0001);
    }

    public function dataProviderForSumOfSquaresDeviations(): array
    {
        return [
            [[3, 6, 7, 11, 12, 13, 17], 136.8571],
            [[6, 11, 12, 14, 15, 20, 21], 162.8571],
            [[1, 2, 3, 6, 7, 11, 12], 112],
            [[1, 2, 3, 4, 5, 6, 7, 8, 9, 0], 82.5],
            [[34, 253, 754, 2342, 75, 23, 876, 4, 1, -34, -345, 754, -377, 3, 0], 6453975.7333],
        ];
    }

    public function testThrowExceptionOnEmptyArraySumOfSquares(): void
    {
        $this->expectException(InvalidArgumentException::class);
        StandardDeviation::sumOfSquares([]);
    }
}
