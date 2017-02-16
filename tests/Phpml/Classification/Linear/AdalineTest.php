<?php

declare(strict_types=1);

namespace tests\Classification\Linear;

use Phpml\Classification\Linear\Adaline;
use Phpml\ModelManager;
use PHPUnit\Framework\TestCase;

class AdalineTest extends TestCase
{
    public function testPredictSingleSample()
    {
        // AND problem
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1]];
        $targets = [0, 0, 0, 1];
        $classifier = new Adaline();
        $classifier->train($samples, $targets);
        $this->assertEquals(0, $classifier->predict([0.1, 0.2]));
        $this->assertEquals(0, $classifier->predict([0.1, 0.99]));
        $this->assertEquals(1, $classifier->predict([1.1, 0.8]));

        // OR problem
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1]];
        $targets = [0, 1, 1, 1];
        $classifier = new Adaline();
        $classifier->train($samples, $targets);
        $this->assertEquals(0, $classifier->predict([0.1, 0.2]));
        $this->assertEquals(1, $classifier->predict([0.1, 0.99]));
        $this->assertEquals(1, $classifier->predict([1.1, 0.8]));

        return $classifier;
    }

    public function testSaveAndRestore()
    {
        // Instantinate new Percetron trained for OR problem
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1]];
        $targets = [0, 1, 1, 1];
        $classifier = new Adaline();
        $classifier->train($samples, $targets);
        $testSamples = [[0, 1], [1, 1], [0.2, 0.1]];
        $predicted = $classifier->predict($testSamples);

        $filename = 'adaline-test-'.rand(100, 999).'-'.uniqid();
        $filepath = tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        $this->assertEquals($classifier, $restoredClassifier);
        $this->assertEquals($predicted, $restoredClassifier->predict($testSamples));
    }
}
