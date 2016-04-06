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

    public function __construct(Dataset $dataset, float $testSize = 0.3)
    {
        if (0 > $testSize || 1 < $testSize) {
            throw InvalidArgumentException::percentNotInRange('testSize');
        }

        // TODO: implement this !
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
}
