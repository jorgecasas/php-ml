<?php
declare(strict_types = 1);

namespace Phpml\Dataset;

class ArrayDataset implements Dataset
{

    /**
     * @var array
     */
    private $samples = [];

    /**
     * @var array
     */
    private $labels = [];

    /**
     * @param array $samples
     * @param array $labels
     */
    public function __construct(array $samples, array $labels)
    {
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
