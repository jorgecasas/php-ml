<?php

declare(strict_types=1);

namespace Phpml\Tests\Classification\Ensemble;

use Phpml\Classification\Classifier;
use Phpml\Classification\DecisionTree;
use Phpml\Classification\Ensemble\Bagging;
use Phpml\Classification\NaiveBayes;
use Phpml\Exception\InvalidArgumentException;
use Phpml\ModelManager;
use PHPUnit\Framework\TestCase;

class BaggingTest extends TestCase
{
    /**
     * @var array
     */
    private $data = [
        ['sunny',       85,    85,    'false',    'Dont_play'],
        ['sunny',       80,    90,    'true',     'Dont_play'],
        ['overcast',    83,    78,    'false',    'Play'],
        ['rain',        70,    96,    'false',    'Play'],
        ['rain',        68,    80,    'false',    'Play'],
        ['rain',        65,    70,    'true',     'Dont_play'],
        ['overcast',    64,    65,    'true',     'Play'],
        ['sunny',       72,    95,    'false',    'Dont_play'],
        ['sunny',       69,    70,    'false',    'Play'],
        ['rain',        75,    80,    'false',    'Play'],
        ['sunny',       75,    70,    'true',     'Play'],
        ['overcast',    72,    90,    'true',     'Play'],
        ['overcast',    81,    75,    'false',    'Play'],
        ['rain',        71,    80,    'true',     'Dont_play'],
    ];

    /**
     * @var array
     */
    private $extraData = [
        ['scorching',  90,     95,    'false',    'Dont_play'],
        ['scorching',   0,      0,    'false',    'Dont_play'],
    ];

    public function testSetSubsetRatioThrowWhenRatioOutOfBounds(): void
    {
        $classifier = $this->getClassifier();

        $this->expectException(InvalidArgumentException::class);
        $classifier->setSubsetRatio(0);
    }

    public function testPredictSingleSample(): void
    {
        [$data, $targets] = $this->getData($this->data);
        $classifier = $this->getClassifier();
        // Testing with default options
        $classifier->train($data, $targets);
        self::assertEquals('Dont_play', $classifier->predict(['sunny', 78, 72, 'false']));
        self::assertEquals('Play', $classifier->predict(['overcast', 60, 60, 'false']));
        self::assertEquals('Dont_play', $classifier->predict(['rain', 60, 60, 'true']));

        [$data, $targets] = $this->getData($this->extraData);
        $classifier->train($data, $targets);
        self::assertEquals('Dont_play', $classifier->predict(['scorching', 95, 90, 'true']));
        self::assertEquals('Play', $classifier->predict(['overcast', 60, 60, 'false']));
    }

    public function testSaveAndRestore(): void
    {
        [$data, $targets] = $this->getData($this->data);
        $classifier = $this->getClassifier(5);
        $classifier->train($data, $targets);

        $testSamples = [['sunny', 78, 72, 'false'], ['overcast', 60, 60, 'false']];
        $predicted = $classifier->predict($testSamples);

        $filename = 'bagging-test-'.random_int(100, 999).'-'.uniqid('', false);
        $filepath = (string) tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        self::assertEquals($classifier, $restoredClassifier);
        self::assertEquals($predicted, $restoredClassifier->predict($testSamples));
    }

    public function testBaseClassifiers(): void
    {
        [$data, $targets] = $this->getData($this->data);
        $baseClassifiers = $this->getAvailableBaseClassifiers();

        foreach ($baseClassifiers as $base => $params) {
            $classifier = $this->getClassifier();
            $classifier->setClassifer($base, $params);
            $classifier->train($data, $targets);

            $baseClassifier = new $base(...array_values($params));
            $baseClassifier->train($data, $targets);
            $testData = [['sunny', 78, 72, 'false'], ['overcast', 60, 60, 'false'], ['rain', 60, 60, 'true']];
            foreach ($testData as $test) {
                $result = $classifier->predict($test);
                $baseResult = $classifier->predict($test);
                self::assertEquals($result, $baseResult);
            }
        }
    }

    /**
     * @return Bagging
     */
    protected function getClassifier(int $numBaseClassifiers = 50): Classifier
    {
        $classifier = new Bagging($numBaseClassifiers);
        $classifier->setSubsetRatio(1.0);
        $classifier->setClassifer(DecisionTree::class, ['depth' => 10]);

        return $classifier;
    }

    protected function getAvailableBaseClassifiers(): array
    {
        return [
            DecisionTree::class => [
                'depth' => 5,
            ],
            NaiveBayes::class => [],
        ];
    }

    private function getData(array $input): array
    {
        // Populating input data to a size large enough
        // for base classifiers that they can work with a subset of it
        $populated = [];
        for ($i = 0; $i < 20; ++$i) {
            $populated = array_merge($populated, $input);
        }

        shuffle($populated);
        $targets = array_column($populated, 4);
        array_walk($populated, function (&$v): void {
            array_splice($v, 4, 1);
        });

        return [$populated, $targets];
    }
}
