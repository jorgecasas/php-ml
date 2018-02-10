<?php

declare(strict_types=1);

namespace Phpml\Tests\DimensionReduction;

use Phpml\Dataset\Demo\IrisDataset;
use Phpml\DimensionReduction\LDA;
use PHPUnit\Framework\TestCase;

class LDATest extends TestCase
{
    public function testLDA(): void
    {
        // Acceptable error
        $epsilon = 0.001;

        // IRIS dataset will be used to train LDA
        $dataset = new IrisDataset();
        $lda = new LDA(null, 2);
        $transformed = $lda->fit($dataset->getSamples(), $dataset->getTargets());

        // Some samples of the Iris data will be checked manually
        // First 3 and last 3 rows from the original dataset
        $data = [
            [5.1, 3.5, 1.4, 0.2],
            [4.9, 3.0, 1.4,    0.2],
            [4.7, 3.2, 1.3, 0.2],
            [6.5, 3.0, 5.2, 2.0],
            [6.2, 3.4, 5.4, 2.3],
            [5.9, 3.0, 5.1, 1.8],
        ];
        $transformed2 = [
            [-1.4922092756753, 1.9047102045574],
            [-1.2576556684358, 1.608414450935],
            [-1.3487505965419, 1.749846351699],
            [1.7759343101456, 2.0371552314006],
            [2.0059819019159, 2.4493123003226],
            [1.701474913008, 1.9037880473772],
        ];

        $control = [];
        $control = array_merge($control, array_slice($transformed, 0, 3));
        $control = array_merge($control, array_slice($transformed, -3));

        $check = function ($row1, $row2) use ($epsilon): void {
            // Due to the fact that the sign of values can be flipped
            // during the calculation of eigenValues, we have to compare
            // absolute value of the values
            $row1 = array_map('abs', $row1);
            $row2 = array_map('abs', $row2);
            $this->assertEquals($row1, $row2, '', $epsilon);
        };
        array_map($check, $control, $transformed2);

        // Fitted LDA object should be able to return same values again
        // for each projected row
        foreach ($data as $i => $row) {
            $newRow = [$transformed2[$i]];
            $newRow2 = $lda->transform($row);

            array_map($check, $newRow, $newRow2);
        }
    }
}
