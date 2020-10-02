<?php

declare(strict_types=1);

namespace Phpml\Tests;

use Phpml\Classification\SVC;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\FeatureSelection\SelectKBest;
use Phpml\ModelManager;
use Phpml\Pipeline;
use Phpml\Preprocessing\Imputer;
use Phpml\Preprocessing\Imputer\Strategy\MeanStrategy;
use Phpml\Preprocessing\Imputer\Strategy\MostFrequentStrategy;
use Phpml\Preprocessing\Normalizer;
use Phpml\Tokenization\WordTokenizer;
use PHPUnit\Framework\TestCase;

class PipelineTest extends TestCase
{
    public function testPipelineConstruction(): void
    {
        $transformers = [
            new TfIdfTransformer(),
        ];
        $estimator = new SVC();

        $pipeline = new Pipeline($transformers, $estimator);

        self::assertEquals($transformers, $pipeline->getTransformers());
        self::assertEquals($estimator, $pipeline->getEstimator());
    }

    public function testPipelineWorkflow(): void
    {
        $transformers = [
            new Imputer(null, new MostFrequentStrategy()),
            new Normalizer(),
        ];
        $estimator = new SVC();

        $samples = [
            [1, -1, 2],
            [2, 0, null],
            [null, 1, -1],
        ];

        $targets = [
            4,
            1,
            4,
        ];

        $pipeline = new Pipeline($transformers, $estimator);
        $pipeline->train($samples, $targets);

        $predicted = $pipeline->predict([[0, 0, 0]]);

        self::assertEquals(4, $predicted[0]);
    }

    public function testPipelineTransformers(): void
    {
        $transformers = [
            new TokenCountVectorizer(new WordTokenizer()),
            new TfIdfTransformer(),
        ];

        $estimator = new SVC();

        $samples = [
            'Hello Paul',
            'Hello Martin',
            'Goodbye Tom',
            'Hello John',
            'Goodbye Alex',
            'Bye Tony',
        ];

        $targets = [
            'greetings',
            'greetings',
            'farewell',
            'greetings',
            'farewell',
            'farewell',
        ];

        $pipeline = new Pipeline($transformers, $estimator);
        $pipeline->train($samples, $targets);

        $expected = ['greetings', 'farewell'];

        $predicted = $pipeline->predict(['Hello Max', 'Goodbye Mark']);

        self::assertEquals($expected, $predicted);
    }

    public function testPipelineTransformersWithTargets(): void
    {
        $samples = [[1, 2, 1], [1, 3, 4], [5, 2, 1], [1, 3, 3], [1, 3, 4], [0, 3, 5]];
        $targets = ['a', 'a', 'a', 'b', 'b', 'b'];

        $pipeline = new Pipeline([$selector = new SelectKBest(2)], new SVC());
        $pipeline->train($samples, $targets);

        self::assertEqualsWithDelta([1.47058823, 4.0, 3.0], $selector->scores(), 0.00000001);
        self::assertEquals(['b'], $pipeline->predict([[1, 3, 5]]));
    }

    public function testPipelineAsTransformer(): void
    {
        $pipeline = new Pipeline([
            new Imputer(null, new MeanStrategy()),
        ]);

        $trainSamples = [
            [10, 20, 30],
            [20, 30, 40],
            [30, 40, 50],
        ];

        $pipeline->fit($trainSamples);

        $testSamples = [
            [null, null, null],
        ];

        $pipeline->transform($testSamples);

        self::assertEquals([[20.0, 30.0, 40.0]], $testSamples);
    }

    public function testSaveAndRestore(): void
    {
        $pipeline = new Pipeline([
            new TokenCountVectorizer(new WordTokenizer()),
            new TfIdfTransformer(),
        ], new SVC());

        $pipeline->train([
            'Hello Paul',
            'Hello Martin',
            'Goodbye Tom',
            'Hello John',
            'Goodbye Alex',
            'Bye Tony',
        ], [
            'greetings',
            'greetings',
            'farewell',
            'greetings',
            'farewell',
            'farewell',
        ]);

        $testSamples = ['Hello Max', 'Goodbye Mark'];
        $predicted = $pipeline->predict($testSamples);

        $filepath = (string) tempnam(sys_get_temp_dir(), uniqid('pipeline-test', true));
        $modelManager = new ModelManager();
        $modelManager->saveToFile($pipeline, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        self::assertEquals($pipeline, $restoredClassifier);
        self::assertEquals($predicted, $restoredClassifier->predict($testSamples));
        unlink($filepath);
    }
}
