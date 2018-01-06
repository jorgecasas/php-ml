<?php

declare(strict_types=1);

namespace Phpml\Tests\DimensionReduction;

use Phpml\DimensionReduction\PCA;
use PHPUnit\Framework\TestCase;

class PCATest extends TestCase
{
    public function testPCA(): void
    {
        // Acceptable error
        $epsilon = 0.001;

        // First a simple example whose result is known and given in
        // http://www.cs.otago.ac.nz/cosc453/student_tutorials/principal_components.pdf
        $data = [
            [2.5, 2.4],
            [0.5, 0.7],
            [2.2, 2.9],
            [1.9, 2.2],
            [3.1, 3.0],
            [2.3, 2.7],
            [2.0, 1.6],
            [1.0, 1.1],
            [1.5, 1.6],
            [1.1, 0.9],
        ];
        $transformed = [
            [-0.827970186], [1.77758033], [-0.992197494],
            [-0.274210416], [-1.67580142], [-0.912949103], [0.0991094375],
            [1.14457216], [0.438046137], [1.22382056], ];

        $pca = new PCA(0.90);
        $reducedData = $pca->fit($data);

        // Due to the fact that the sign of values can be flipped
        // during the calculation of eigenValues, we have to compare
        // absolute value of the values
        array_map(function ($val1, $val2) use ($epsilon): void {
            $this->assertEquals(abs($val1), abs($val2), '', $epsilon);
        }, $transformed, $reducedData);

        // Test fitted PCA object to transform an arbitrary sample of the
        // same dimensionality with the original dataset
        foreach ($data as $i => $row) {
            $newRow = [[$transformed[$i]]];
            $newRow2 = $pca->transform($row);

            array_map(function ($val1, $val2) use ($epsilon): void {
                $this->assertEquals(abs($val1), abs($val2), '', $epsilon);
            }, $newRow, $newRow2);
        }
    }
}
