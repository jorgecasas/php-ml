<?php

declare(strict_types=1);

namespace Phpml\Tests\SupportVectorMachine;

use Phpml\SupportVectorMachine\DataTransformer;
use PHPUnit\Framework\TestCase;

class DataTransformerTest extends TestCase
{
    public function testTransformDatasetToTrainingSet(): void
    {
        $samples = [[1, 1], [2, 1], [3, 2], [4, 5]];
        $labels = ['a', 'a', 'b', 'b'];

        $trainingSet =
            '0 1:1 2:1 '.PHP_EOL.
            '0 1:2 2:1 '.PHP_EOL.
            '1 1:3 2:2 '.PHP_EOL.
            '1 1:4 2:5 '.PHP_EOL
        ;

        $this->assertEquals($trainingSet, DataTransformer::trainingSet($samples, $labels));
    }

    public function testTransformSamplesToTestSet(): void
    {
        $samples = [[1, 1], [2, 1], [3, 2], [4, 5]];

        $testSet =
            '0 1:1 2:1 '.PHP_EOL.
            '0 1:2 2:1 '.PHP_EOL.
            '0 1:3 2:2 '.PHP_EOL.
            '0 1:4 2:5 '.PHP_EOL
        ;

        $this->assertEquals($testSet, DataTransformer::testSet($samples));
    }

    public function testPredictions(): void
    {
        $labels = ['a', 'a', 'b', 'b'];
        $rawPredictions = implode(PHP_EOL, [0, 1, 0, 0]);

        $predictions = ['a', 'b', 'a', 'a'];

        $this->assertEquals($predictions, DataTransformer::predictions($rawPredictions, $labels));
    }

    public function testProbabilities(): void
    {
        $labels = ['a', 'b', 'c'];
        $rawPredictions = implode(PHP_EOL, [
            'labels 0 1 2',
            '1 0.1 0.7 0.2',
            '2 0.2 0.3 0.5',
            '0 0.6 0.1 0.3',
        ]);

        $probabilities = [
            [
                'a' => 0.1,
                'b' => 0.7,
                'c' => 0.2,
            ],
            [
                'a' => 0.2,
                'b' => 0.3,
                'c' => 0.5,
            ],
            [
                'a' => 0.6,
                'b' => 0.1,
                'c' => 0.3,
            ],
        ];

        $this->assertEquals($probabilities, DataTransformer::probabilities($rawPredictions, $labels));
    }
}
