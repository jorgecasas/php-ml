<?php

declare (strict_types = 1);

namespace tests\SupportVectorMachine;

use Phpml\SupportVectorMachine\DataTransformer;

class DataTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformDatasetToTrainingSet()
    {
        $samples = [[1, 1], [2, 1], [3, 2], [4, 5]];
        $labels = ['a', 'a', 'b', 'b'];

        $trainingSet =
            '0 0:1 1:1 '.PHP_EOL.
            '0 0:2 1:1 '.PHP_EOL.
            '1 0:3 1:2 '.PHP_EOL.
            '1 0:4 1:5 '.PHP_EOL
        ;

        $this->assertEquals($trainingSet, DataTransformer::trainingSet($samples, $labels));
    }
}
