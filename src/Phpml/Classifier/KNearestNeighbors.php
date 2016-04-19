<?php

declare (strict_types = 1);

namespace Phpml\Classifier;

use Phpml\Classifier\Traits\Predictable;
use Phpml\Classifier\Traits\Trainable;
use Phpml\Metric\Distance;
use Phpml\Metric\Distance\Euclidean;

class KNearestNeighbors implements Classifier
{
    use Trainable, Predictable;

    /**
     * @var int
     */
    private $k;

    /**
     * @var Distance
     */
    private $distanceMetric;

    /**
     * @param int           $k
     * @param Distance|null $distanceMetric (if null then Euclidean distance as default)
     */
    public function __construct(int $k = 3, Distance $distanceMetric = null)
    {
        if (null === $distanceMetric) {
            $distanceMetric = new Euclidean();
        }

        $this->k = $k;
        $this->samples = [];
        $this->labels = [];
        $this->distanceMetric = $distanceMetric;
    }

    /**
     * @param array $sample
     *
     * @return mixed
     */
    protected function predictSample(array $sample)
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
            $distances[$index] = $this->distanceMetric->distance($sample, $neighbor);
        }

        asort($distances);

        return array_slice($distances, 0, $this->k, true);
    }
}
