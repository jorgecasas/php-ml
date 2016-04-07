<?php

declare (strict_types = 1);

namespace Phpml\CrossValidation;

use Phpml\Dataset\Dataset;
use Phpml\Exception\InvalidArgumentException;

class RandomSplit
{
    /**
     * @var array
     */
    private $trainSamples = [];

    /**
     * @var array
     */
    private $testSamples = [];

    /**
     * @var array
     */
    private $trainLabels = [];

    /**
     * @var array
     */
    private $testLabels = [];

    /**
     * @param Dataset $dataset
     * @param float $testSize
     * @param int $seed
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Dataset $dataset, float $testSize = 0.3, int $seed = null)
    {
        if (0 >= $testSize || 1 <= $testSize) {
            throw InvalidArgumentException::percentNotInRange('testSize');
        }
        $this->seedGenerator($seed);

        $samples = $dataset->getSamples();
        $labels = $dataset->getLabels();
        $datasetSize = count($samples);

        for($i=$datasetSize; $i>0; $i--) {
            $key = mt_rand(0, $datasetSize-1);
            $setName = count($this->testSamples) / $datasetSize >= $testSize ? 'train' : 'test';

            $this->{$setName.'Samples'}[] = $samples[$key];
            $this->{$setName.'Labels'}[] = $labels[$key];

            $samples = array_values($samples);
            $labels = array_values($labels);
        }
    }

    /**
     * @return array
     */
    public function getTrainSamples()
    {
        return $this->trainSamples;
    }

    /**
     * @return array
     */
    public function getTestSamples()
    {
        return $this->testSamples;
    }

    /**
     * @return array
     */
    public function getTrainLabels()
    {
        return $this->trainLabels;
    }

    /**
     * @return array
     */
    public function getTestLabels()
    {
        return $this->testLabels;
    }

    /**
     * @param int|null $seed
     */
    private function seedGenerator(int $seed = null)
    {
        if (null === $seed) {
            mt_srand();
        } else {
            mt_srand($seed);
        }
    }
}
