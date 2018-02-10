<?php

declare(strict_types=1);

namespace Phpml\Tests\Math\Statistic;

use Phpml\Math\Statistic\Variance;
use PHPUnit\Framework\TestCase;

final class VarianceTest extends TestCase
{
    /**
     * @dataProvider dataProviderForPopulationVariance
     */
    public function testVarianceFromInt(array $numbers, float $variance): void
    {
        self::assertEquals($variance, Variance::population($numbers), '', 0.001);
    }

    public function dataProviderForPopulationVariance()
    {
        return [
            [[0, 0, 0, 0, 0, 1], 0.138],
            [[-11, 0, 10, 20, 30], 208.16],
            [[7, 8, 9, 10, 11, 12, 13], 4.0],
            [[300, 570, 170, 730, 300], 41944],
            [[-4, 2, 7, 8, 3], 18.16],
            [[3, 7, 34, 25, 46, 7754, 3, 6], 6546331.937],
            [[4, 6, 1, 1, 1, 1, 2, 2, 1, 3], 2.56],
            [[-3732, 5, 27, 9248, -174], 18741676.56],
            [[-554, -555, -554, -554, -555, -555, -556], 0.4897],
        ];
    }
}
