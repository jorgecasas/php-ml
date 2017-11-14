<?php

declare(strict_types=1);

namespace Phpml\Clustering;

use Phpml\Math\Distance;
use Phpml\Math\Distance\Euclidean;

class DBSCAN implements Clusterer
{
    /**
     * @var float
     */
    private $epsilon;

    /**
     * @var int
     */
    private $minSamples;

    /**
     * @var Distance
     */
    private $distanceMetric;

    public function __construct(float $epsilon = 0.5, int $minSamples = 3, ?Distance $distanceMetric = null)
    {
        if (null === $distanceMetric) {
            $distanceMetric = new Euclidean();
        }

        $this->epsilon = $epsilon;
        $this->minSamples = $minSamples;
        $this->distanceMetric = $distanceMetric;
    }

    public function cluster(array $samples) : array
    {
        $clusters = [];
        $visited = [];

        foreach ($samples as $index => $sample) {
            if (isset($visited[$index])) {
                continue;
            }
            $visited[$index] = true;

            $regionSamples = $this->getSamplesInRegion($sample, $samples);
            if (count($regionSamples) >= $this->minSamples) {
                $clusters[] = $this->expandCluster($regionSamples, $visited);
            }
        }

        return $clusters;
    }

    private function getSamplesInRegion(array $localSample, array $samples) : array
    {
        $region = [];

        foreach ($samples as $index => $sample) {
            if ($this->distanceMetric->distance($localSample, $sample) < $this->epsilon) {
                $region[$index] = $sample;
            }
        }

        return $region;
    }

    private function expandCluster(array $samples, array &$visited) : array
    {
        $cluster = [];

        $clusterMerge = [[]];
        foreach ($samples as $index => $sample) {
            if (!isset($visited[$index])) {
                $visited[$index] = true;
                $regionSamples = $this->getSamplesInRegion($sample, $samples);
                if (count($regionSamples) > $this->minSamples) {
                    $clusterMerge[] = $regionSamples;
                }
            }

            $cluster[$index] = $sample;
        }
        $cluster = \array_merge($cluster, ...$clusterMerge);

        return $cluster;
    }
}
