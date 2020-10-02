<?php

declare(strict_types=1);

namespace Phpml\Tests\Preprocessing;

use Phpml\Preprocessing\ColumnFilter;
use PHPUnit\Framework\TestCase;

final class ColumnFilterTest extends TestCase
{
    public function testFilterColumns(): void
    {
        $datasetColumns = ['age', 'income', 'kids', 'beersPerWeek'];
        $filterColumns = ['income', 'beersPerWeek'];
        $samples = [
            [21, 100000, 1, 4],
            [35, 120000, 0, 12],
            [33, 200000, 4, 0],
        ];

        $filter = new ColumnFilter($datasetColumns, $filterColumns);
        $filter->transform($samples);

        self::assertEquals([[100000, 4], [120000, 12], [200000, 0]], $samples);
    }
}
