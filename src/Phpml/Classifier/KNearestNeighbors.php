<?php

declare (strict_types = 1);

namespace Phpml\Classifier;

use Phpml\Metric\Distance;

class KNearestNeighbors implements Classifier
{
    /**
     * @var int
     */
    private $k;

    /**
     * @var array
     */
    private $samples;

    /**
     * @var array
     */
    private $labels;

    /**
     * @param int $k
     */
    public function __construct(int $k = 3)
    {
        $this->k = $k;
        $this->samples = [];
        $this->labels = [];
    }

    /**
     * @param array $samples
     * @param array $labels
     */
    public function train(array $samples, array $labels)
    {
        $this->samples = $samples;
        $this->labels = $labels;
    }

    /**
     * @param array $sample
     *
     * @return mixed
     */
    public function predict(array $sample)
    {
        $distances = $this->kNeighborsDistances($sample);

        $predictions = array_combine(array_values($this->labels), array_fill(0, count($this->labels), 0));

        foreach ($distances as $index => $distance) {
            ++$predictions[$this->labels[$index]];
        }

        arsort($predictions);
        reset($predictions);

        return key($predictions);
    }

    /**
     * @param array $sample
     *
     * @return array
     *
     * @throws \Phpml\Exception\InvalidArgumentException
     */
    private function kNeighborsDistances(array $sample): array
    {
        $distances = [];

        foreach ($this->samples as $index => $neighbor) {
            $distances[$index] = Distance::euclidean($sample, $neighbor);
        }

        asort($distances);

        return array_slice($distances, 0, $this->k, true);
    }
}
