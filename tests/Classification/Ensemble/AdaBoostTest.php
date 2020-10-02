<?php

declare(strict_types=1);

namespace Phpml\Tests\Classification\Ensemble;

use Phpml\Classification\Ensemble\AdaBoost;
use Phpml\Exception\InvalidArgumentException;
use Phpml\ModelManager;
use PHPUnit\Framework\TestCase;

class AdaBoostTest extends TestCase
{
    public function testTrainThrowWhenMultiClassTargetGiven(): void
    {
        $samples = [
            [0, 0],
            [0.5, 0.5],
            [1, 1],
        ];
        $targets = [
            0,
            1,
            2,
        ];

        $classifier = new AdaBoost();

        $this->expectException(InvalidArgumentException::class);
        $classifier->train($samples, $targets);
    }

    public function testPredictSingleSample(): void
    {
        // AND problem
        $samples = [[0.1, 0.3], [1, 0], [0, 1], [1, 1], [0.9, 0.8], [1.1, 1.1]];
        $targets = [0, 0, 0, 1, 1, 1];
        $classifier = new AdaBoost();
        $classifier->train($samples, $targets);
        self::assertEquals(0, $classifier->predict([0.1, 0.2]));
        self::assertEquals(0, $classifier->predict([0.1, 0.99]));
        self::assertEquals(1, $classifier->predict([1.1, 0.8]));

        // OR problem
        $samples = [[0, 0], [0.1, 0.2], [0.2, 0.1], [1, 0], [0, 1], [1, 1]];
        $targets = [0, 0, 0, 1, 1, 1];
        $classifier = new AdaBoost();
        $classifier->train($samples, $targets);
        self::assertEquals(0, $classifier->predict([0.1, 0.2]));
        self::assertEquals(1, $classifier->predict([0.1, 0.99]));
        self::assertEquals(1, $classifier->predict([1.1, 0.8]));

        // XOR problem
        $samples = [[0.1, 0.2], [1., 1.], [0.9, 0.8], [0., 1.], [1., 0.], [0.2, 0.8]];
        $targets = [0, 0, 0, 1, 1, 1];
        $classifier = new AdaBoost(5);
        $classifier->train($samples, $targets);
        self::assertEquals(0, $classifier->predict([0.1, 0.1]));
        self::assertEquals(1, $classifier->predict([0, 0.999]));
        self::assertEquals(0, $classifier->predict([1.1, 0.8]));
    }

    public function testSaveAndRestore(): void
    {
        // Instantinate new Percetron trained for OR problem
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1]];
        $targets = [0, 1, 1, 1];
        $classifier = new AdaBoost();
        $classifier->train($samples, $targets);
        $testSamples = [[0, 1], [1, 1], [0.2, 0.1]];
        $predicted = $classifier->predict($testSamples);

        $filename = 'adaboost-test-'.random_int(100, 999).'-'.uniqid('', false);
        $filepath = (string) tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        self::assertEquals($classifier, $restoredClassifier);
        self::assertEquals($predicted, $restoredClassifier->predict($testSamples));
    }
}
