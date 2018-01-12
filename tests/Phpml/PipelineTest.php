<?php

declare(strict_types=1);

namespace Phpml\Tests;

use Phpml\Classification\SVC;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\ModelManager;
use Phpml\Pipeline;
use Phpml\Preprocessing\Imputer;
use Phpml\Preprocessing\Imputer\Strategy\MostFrequentStrategy;
use Phpml\Preprocessing\Normalizer;
use Phpml\Regression\SVR;
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

        $this->assertEquals($transformers, $pipeline->getTransformers());
        $this->assertEquals($estimator, $pipeline->getEstimator());
    }

    public function testPipelineEstimatorSetter(): void
    {
        $pipeline = new Pipeline([new TfIdfTransformer()], new SVC());

        $estimator = new SVR();
        $pipeline->setEstimator($estimator);

        $this->assertEquals($estimator, $pipeline->getEstimator());
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

        $this->assertEquals(4, $predicted[0]);
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

        $this->assertEquals($expected, $predicted);
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

        $filepath = tempnam(sys_get_temp_dir(), uniqid('pipeline-test', true));
        $modelManager = new ModelManager();
        $modelManager->saveToFile($pipeline, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        $this->assertEquals($pipeline, $restoredClassifier);
        $this->assertEquals($predicted, $restoredClassifier->predict($testSamples));
        unlink($filepath);
    }
}
