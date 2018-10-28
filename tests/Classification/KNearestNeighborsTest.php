<?php

declare(strict_types=1);

namespace Phpml\Tests\Classification;

use Phpml\Classification\KNearestNeighbors;
use Phpml\Math\Distance\Chebyshev;
use Phpml\ModelManager;
use PHPUnit\Framework\TestCase;

class KNearestNeighborsTest extends TestCase
{
    public function testPredictSingleSampleWithDefaultK(): void
    {
        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $classifier = new KNearestNeighbors();
        $classifier->train($samples, $labels);

        self::assertEquals('b', $classifier->predict([3, 2]));
        self::assertEquals('b', $classifier->predict([5, 1]));
        self::assertEquals('b', $classifier->predict([4, 3]));
        self::assertEquals('b', $classifier->predict([4, -5]));

        self::assertEquals('a', $classifier->predict([2, 3]));
        self::assertEquals('a', $classifier->predict([1, 2]));
        self::assertEquals('a', $classifier->predict([1, 5]));
        self::assertEquals('a', $classifier->predict([3, 10]));
    }

    public function testPredictArrayOfSamples(): void
    {
        $trainSamples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $trainLabels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $testSamples = [[3, 2], [5, 1], [4, 3], [4, -5], [2, 3], [1, 2], [1, 5], [3, 10]];
        $testLabels = ['b', 'b', 'b', 'b', 'a', 'a', 'a', 'a'];

        $classifier = new KNearestNeighbors();
        $classifier->train($trainSamples, $trainLabels);
        $predicted = $classifier->predict($testSamples);

        self::assertEquals($testLabels, $predicted);
    }

    public function testPredictArrayOfSamplesUsingChebyshevDistanceMetric(): void
    {
        $trainSamples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $trainLabels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $testSamples = [[3, 2], [5, 1], [4, 3], [4, -5], [2, 3], [1, 2], [1, 5], [3, 10]];
        $testLabels = ['b', 'b', 'b', 'b', 'a', 'a', 'a', 'a'];

        $classifier = new KNearestNeighbors(3, new Chebyshev());
        $classifier->train($trainSamples, $trainLabels);
        $predicted = $classifier->predict($testSamples);

        self::assertEquals($testLabels, $predicted);
    }

    public function testSaveAndRestore(): void
    {
        $trainSamples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $trainLabels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $testSamples = [[3, 2], [5, 1], [4, 3], [4, -5], [2, 3], [1, 2], [1, 5], [3, 10]];

        // Using non-default constructor parameters to check that their values are restored.
        $classifier = new KNearestNeighbors(3, new Chebyshev());
        $classifier->train($trainSamples, $trainLabels);
        $predicted = $classifier->predict($testSamples);

        $filename = 'knearest-neighbors-test-'.random_int(100, 999).'-'.uniqid('', false);
        $filepath = (string) tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        self::assertEquals($classifier, $restoredClassifier);
        self::assertEquals($predicted, $restoredClassifier->predict($testSamples));
    }
}
