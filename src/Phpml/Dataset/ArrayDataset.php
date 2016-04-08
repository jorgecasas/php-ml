<?php

declare (strict_types = 1);

namespace Phpml\Dataset;

use Phpml\Exception\InvalidArgumentException;

class ArrayDataset implements Dataset
{
    /**
     * @var array
     */
    protected $samples = [];

    /**
     * @var array
     */
    protected $labels = [];

    /**
     * @param array $samples
     * @param array $labels
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $samples, array $labels)
    {
        if (count($samples) != count($labels)) {
            throw InvalidArgumentException::sizeNotMatch();
        }

        $this->samples = $samples;
        $this->labels = $labels;
    }

    /**
     * @return array
     */
    public function getSamples(): array
    {
        return $this->samples;
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        return $this->labels;
    }
}
