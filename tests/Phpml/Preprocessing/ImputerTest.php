<?php

declare (strict_types = 1);

namespace tests\Preprocessing;

use Phpml\Preprocessing\Imputer;
use Phpml\Preprocessing\Imputer\Strategy\MeanStrategy;

class ImputerTest extends \PHPUnit_Framework_TestCase
{
    public function testCompletingMissingValuesWithMeanStrategyOnColumnAxis()
    {
        $data = [
            [1, null, 3, 4],
            [4, 3, 2, 1],
            [null, 6, 7, 8],
            [8, 7, null, 5],
        ];

        $imputeData = [
            [1, 5.33, 3, 4],
            [4, 3, 2, 1],
            [4.33, 6, 7, 8],
            [8, 7, 4, 5],
        ];

        $imputer = new Imputer(null, new MeanStrategy(), Imputer::AXIS_COLUMN);
        $imputer->preprocess($data);

        $this->assertEquals($imputeData, $data, '', $delta = 0.01);
    }

    public function testCompletingMissingValuesWithMeanStrategyOnRowAxis()
    {
        $data = [
            [1, null, 3, 4],
            [4, 3, 2, 1],
            [null, 6, 7, 8],
            [8, 7, null, 5],
        ];

        $imputeData = [
            [1, 2.66, 3, 4],
            [4, 3, 2, 1],
            [7, 6, 7, 8],
            [8, 7, 6.66, 5],
        ];

        $imputer = new Imputer(null, new MeanStrategy(), Imputer::AXIS_ROW);
        $imputer->preprocess($data);

        $this->assertEquals($imputeData, $data, '', $delta = 0.01);
    }
}
