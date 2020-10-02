<?php

declare(strict_types=1);

namespace Phpml\Tests;

use Phpml\Exception\InvalidArgumentException;
use Phpml\FeatureUnion;
use Phpml\Pipeline;
use Phpml\Preprocessing\ColumnFilter;
use Phpml\Preprocessing\Imputer;
use Phpml\Preprocessing\Imputer\Strategy\MeanStrategy;
use Phpml\Preprocessing\LabelEncoder;
use Phpml\Preprocessing\LambdaTransformer;
use Phpml\Preprocessing\NumberConverter;
use PHPUnit\Framework\TestCase;

final class FeatureUnionTest extends TestCase
{
    public function testFitAndTransform(): void
    {
        $columns = ['age', 'income', 'sex'];
        $samples = [
            ['23', '100000', 'male'],
            ['23', '200000', 'female'],
            ['43', '150000', 'female'],
            ['33', 'n/a', 'male'],
        ];
        $targets = ['1', '2', '1', '3'];

        $union = new FeatureUnion([
            new Pipeline([
                new ColumnFilter($columns, ['sex']),
                new LambdaTransformer(function (array $sample) {
                    return $sample[0];
                }),
                new LabelEncoder(),
            ]),
            new Pipeline([
                new ColumnFilter($columns, ['age', 'income']),
                new NumberConverter(true),
                new Imputer(null, new MeanStrategy(), Imputer::AXIS_COLUMN),
            ]),
        ]);

        $union->fitAndTransform($samples, $targets);

        self::assertEquals([
            [0, 23.0, 100000.0],
            [1, 23.0, 200000.0],
            [1, 43.0, 150000.0],
            [0, 33.0, 150000.0],
        ], $samples);
        self::assertEquals([1, 2, 1, 3], $targets);
    }

    public function testFitAndTransformSeparate(): void
    {
        $columns = ['age', 'income', 'sex'];
        $trainSamples = [
            ['23', '100000', 'male'],
            ['23', '200000', 'female'],
            ['43', '150000', 'female'],
            ['33', 'n/a', 'male'],
        ];
        $testSamples = [
            ['43', '500000', 'female'],
            ['13', 'n/a', 'male'],
            ['53', 'n/a', 'male'],
            ['43', 'n/a', 'female'],
        ];

        $union = new FeatureUnion([
            new Pipeline([
                new ColumnFilter($columns, ['sex']),
                new LambdaTransformer(function (array $sample) {
                    return $sample[0];
                }),
                new LabelEncoder(),
            ]),
            new Pipeline([
                new ColumnFilter($columns, ['age', 'income']),
                new NumberConverter(),
                new Imputer(null, new MeanStrategy(), Imputer::AXIS_COLUMN),
            ]),
        ]);

        $union->fit($trainSamples);
        $union->transform($testSamples);

        self::assertEquals([
            [1, 43.0, 500000.0],
            [0, 13.0, 150000.0],
            [0, 53.0, 150000.0],
            [1, 43.0, 150000.0],
        ], $testSamples);
    }

    public function testNotAllowForEmptyPipelines(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new FeatureUnion([]);
    }
}
