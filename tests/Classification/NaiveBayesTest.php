<?php

declare(strict_types=1);

namespace Phpml\Tests\Classification;

use Phpml\Classification\NaiveBayes;
use Phpml\Exception\InvalidArgumentException;
use Phpml\ModelManager;
use PHPUnit\Framework\TestCase;

class NaiveBayesTest extends TestCase
{
    public function testPredictSingleSample(): void
    {
        $samples = [[5, 1, 1], [1, 5, 1], [1, 1, 5]];
        $labels = ['a', 'b', 'c'];

        $classifier = new NaiveBayes();
        $classifier->train($samples, $labels);

        self::assertEquals('a', $classifier->predict([3, 1, 1]));
        self::assertEquals('b', $classifier->predict([1, 4, 1]));
        self::assertEquals('c', $classifier->predict([1, 1, 6]));
    }

    public function testPredictArrayOfSamples(): void
    {
        $trainSamples = [[5, 1, 1], [1, 5, 1], [1, 1, 5]];
        $trainLabels = ['a', 'b', 'c'];

        $testSamples = [[3, 1, 1], [5, 1, 1], [4, 3, 8], [1, 1, 2], [2, 3, 2], [1, 2, 1], [9, 5, 1], [3, 1, 2]];
        $testLabels = ['a', 'a', 'c', 'c', 'b', 'b', 'a', 'a'];

        $classifier = new NaiveBayes();
        $classifier->train($trainSamples, $trainLabels);
        $predicted = $classifier->predict($testSamples);

        self::assertEquals($testLabels, $predicted);

        // Feed an extra set of training data.
        $samples = [[1, 1, 6]];
        $labels = ['d'];
        $classifier->train($samples, $labels);

        $testSamples = [[1, 1, 6], [5, 1, 1]];
        $testLabels = ['d', 'a'];
        self::assertEquals($testLabels, $classifier->predict($testSamples));
    }

    public function testSaveAndRestore(): void
    {
        $trainSamples = [[5, 1, 1], [1, 5, 1], [1, 1, 5]];
        $trainLabels = ['a', 'b', 'c'];

        $testSamples = [[3, 1, 1], [5, 1, 1], [4, 3, 8]];

        $classifier = new NaiveBayes();
        $classifier->train($trainSamples, $trainLabels);
        $predicted = $classifier->predict($testSamples);

        $filename = 'naive-bayes-test-'.random_int(100, 999).'-'.uniqid('', false);
        $filepath = (string) tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        self::assertEquals($classifier, $restoredClassifier);
        self::assertEquals($predicted, $restoredClassifier->predict($testSamples));
    }

    public function testPredictSimpleNumericLabels(): void
    {
        $samples = [[5, 1, 1], [1, 5, 1], [1, 1, 5]];
        $labels = ['1996', '1997', '1998'];

        $classifier = new NaiveBayes();
        $classifier->train($samples, $labels);

        self::assertEquals('1996', $classifier->predict([3, 1, 1]));
        self::assertEquals('1997', $classifier->predict([1, 4, 1]));
        self::assertEquals('1998', $classifier->predict([1, 1, 6]));
    }

    public function testPredictArrayOfSamplesNumericalLabels(): void
    {
        $trainSamples = [[5, 1, 1], [1, 5, 1], [1, 1, 5]];
        $trainLabels = ['1996', '1997', '1998'];

        $testSamples = [[3, 1, 1], [5, 1, 1], [4, 3, 8], [1, 1, 2], [2, 3, 2], [1, 2, 1], [9, 5, 1], [3, 1, 2]];
        $testLabels = ['1996', '1996', '1998', '1998', '1997', '1997', '1996', '1996'];

        $classifier = new NaiveBayes();
        $classifier->train($trainSamples, $trainLabels);
        $predicted = $classifier->predict($testSamples);

        self::assertEquals($testLabels, $predicted);

        // Feed an extra set of training data.
        $samples = [[1, 1, 6]];
        $labels = ['1999'];
        $classifier->train($samples, $labels);

        $testSamples = [[1, 1, 6], [5, 1, 1]];
        $testLabels = ['1999', '1996'];
        self::assertEquals($testLabels, $classifier->predict($testSamples));
    }

    public function testSaveAndRestoreNumericLabels(): void
    {
        $trainSamples = [[5, 1, 1], [1, 5, 1], [1, 1, 5]];
        $trainLabels = ['1996', '1997', '1998'];

        $testSamples = [[3, 1, 1], [5, 1, 1], [4, 3, 8]];

        $classifier = new NaiveBayes();
        $classifier->train($trainSamples, $trainLabels);
        $predicted = $classifier->predict($testSamples);

        $filename = 'naive-bayes-test-'.random_int(100, 999).'-'.uniqid('', false);
        $filepath = (string) tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        self::assertEquals($classifier, $restoredClassifier);
        self::assertEquals($predicted, $restoredClassifier->predict($testSamples));
    }

    public function testInconsistentFeaturesInSamples(): void
    {
        $trainSamples = [[5, 1, 1], [1, 5, 1], [1, 1, 5]];
        $trainLabels = ['1996', '1997', '1998'];

        $testSamples = [[3, 1, 1], [5, 1], [4, 3, 8]];

        $classifier = new NaiveBayes();
        $classifier->train($trainSamples, $trainLabels);

        $this->expectException(InvalidArgumentException::class);

        $classifier->predict($testSamples);
    }
}
