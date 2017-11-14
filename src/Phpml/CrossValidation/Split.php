<?php

declare(strict_types=1);

namespace Phpml\CrossValidation;

use Phpml\Dataset\Dataset;
use Phpml\Exception\InvalidArgumentException;

abstract class Split
{
    /**
     * @var array
     */
    protected $trainSamples = [];

    /**
     * @var array
     */
    protected $testSamples = [];

    /**
     * @var array
     */
    protected $trainLabels = [];

    /**
     * @var array
     */
    protected $testLabels = [];

    public function __construct(Dataset $dataset, float $testSize = 0.3, ?int $seed = null)
    {
        if (0 >= $testSize || 1 <= $testSize) {
            throw InvalidArgumentException::percentNotInRange('testSize');
        }
        $this->seedGenerator($seed);

        $this->splitDataset($dataset, $testSize);
    }

    abstract protected function splitDataset(Dataset $dataset, float $testSize);

    public function getTrainSamples() : array
    {
        return $this->trainSamples;
    }

    public function getTestSamples() : array
    {
        return $this->testSamples;
    }

    public function getTrainLabels() : array
    {
        return $this->trainLabels;
    }

    public function getTestLabels() : array
    {
        return $this->testLabels;
    }

    protected function seedGenerator(?int $seed = null): void
    {
        if (null === $seed) {
            mt_srand();
        } else {
            mt_srand($seed);
        }
    }
}
