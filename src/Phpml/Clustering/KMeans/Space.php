<?php

declare (strict_types = 1);

namespace Phpml\Clustering\KMeans;

use Phpml\Clustering\KMeans;
use SplObjectStorage;
use LogicException;
use InvalidArgumentException;

class Space extends SplObjectStorage
{
    /**
     * @var int
     */
    protected $dimension;

    /**
     * @param $dimension
     */
    public function __construct($dimension)
    {
        if ($dimension < 1) {
            throw new LogicException('a space dimension cannot be null or negative');
        }

        $this->dimension = $dimension;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $points = [];
        foreach ($this as $point) {
            $points[] = $point->toArray();
        }

        return ['points' => $points];
    }

    /**
     * @param array $coordinates
     *
     * @return Point
     */
    public function newPoint(array $coordinates)
    {
        if (count($coordinates) != $this->dimension) {
            throw new LogicException('('.implode(',', $coordinates).') is not a point of this space');
        }

        return new Point($coordinates);
    }

    /**
     * @param array $coordinates
     * @param null  $data
     */
    public function addPoint(array $coordinates, $data = null)
    {
        return $this->attach($this->newPoint($coordinates), $data);
    }

    /**
     * @param object $point
     * @param null   $data
     */
    public function attach($point, $data = null)
    {
        if (!$point instanceof Point) {
            throw new InvalidArgumentException('can only attach points to spaces');
        }

        return parent::attach($point, $data);
    }

    /**
     * @return int
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * @return array|bool
     */
    public function getBoundaries()
    {
        if (!count($this)) {
            return false;
        }

        $min = $this->newPoint(array_fill(0, $this->dimension, null));
        $max = $this->newPoint(array_fill(0, $this->dimension, null));

        foreach ($this as $point) {
            for ($n = 0; $n < $this->dimension; ++$n) {
                ($min[$n] > $point[$n] || $min[$n] === null) && $min[$n] = $point[$n];
                ($max[$n] < $point[$n] || $max[$n] === null) && $max[$n] = $point[$n];
            }
        }

        return array($min, $max);
    }

    /**
     * @param Point $min
     * @param Point $max
     *
     * @return Point
     */
    public function getRandomPoint(Point $min, Point $max)
    {
        $point = $this->newPoint(array_fill(0, $this->dimension, null));

        for ($n = 0; $n < $this->dimension; ++$n) {
            $point[$n] = rand($min[$n], $max[$n]);
        }

        return $point;
    }

    /**
     * @param $nbClusters
     * @param int  $seed
     * @param null $iterationCallback
     *
     * @return array|Cluster[]
     */
    public function solve($nbClusters, $seed = KMeans::INIT_RANDOM, $iterationCallback = null)
    {
        if ($iterationCallback && !is_callable($iterationCallback)) {
            throw new InvalidArgumentException('invalid iteration callback');
        }

        // initialize K clusters
        $clusters = $this->initializeClusters($nbClusters, $seed);

        // there's only one cluster, clusterization has no meaning
        if (count($clusters) == 1) {
            return $clusters[0];
        }

        // until convergence is reached
        do {
            $iterationCallback && $iterationCallback($this, $clusters);
        } while ($this->iterate($clusters));

        // clustering is done.
        return $clusters;
    }

    /**
     * @param $nbClusters
     * @param $seed
     *
     * @return array
     */
    protected function initializeClusters($nbClusters, $seed)
    {
        if ($nbClusters <= 0) {
            throw new InvalidArgumentException('invalid clusters number');
        }

        switch ($seed) {
            // the default seeding method chooses completely random centroid
            case KMeans::INIT_RANDOM:
                // get the space boundaries to avoid placing clusters centroid too far from points
                list($min, $max) = $this->getBoundaries();

                // initialize N clusters with a random point within space boundaries
                for ($n = 0; $n < $nbClusters; ++$n) {
                    $clusters[] = new Cluster($this, $this->getRandomPoint($min, $max)->getCoordinates());
                }

                break;

            // the DASV seeding method consists of finding good initial centroids for the clusters
            case KMeans::INIT_KMEANS_PLUS_PLUS:
                // find a random point
                $position = rand(1, count($this));
                for ($i = 1, $this->rewind(); $i < $position && $this->valid(); $i++, $this->next());
                $clusters[] = new Cluster($this, $this->current()->getCoordinates());

                // retains the distances between points and their closest clusters
                $distances = new SplObjectStorage();

                // create k clusters
                for ($i = 1; $i < $nbClusters; ++$i) {
                    $sum = 0;

                    // for each points, get the distance with the closest centroid already choosen
                    foreach ($this as $point) {
                        $distance = $point->getDistanceWith($point->getClosest($clusters));
                        $sum += $distances[$point] = $distance;
                    }

                    // choose a new random point using a weighted probability distribution
                    $sum = rand(0, (int) $sum);
                    foreach ($this as $point) {
                        if (($sum -= $distances[$point]) > 0) {
                            continue;
                        }

                        $clusters[] = new Cluster($this, $point->getCoordinates());
                        break;
                    }
                }

                break;
        }

        // assing all points to the first cluster
        $clusters[0]->attachAll($this);

        return $clusters;
    }

    /**
     * @param $clusters
     *
     * @return bool
     */
    protected function iterate($clusters)
    {
        $continue = false;

        // migration storages
        $attach = new SplObjectStorage();
        $detach = new SplObjectStorage();

        // calculate proximity amongst points and clusters
        foreach ($clusters as $cluster) {
            foreach ($cluster as $point) {
                // find the closest cluster
                $closest = $point->getClosest($clusters);

                // move the point from its old cluster to its closest
                if ($closest !== $cluster) {
                    isset($attach[$closest]) || $attach[$closest] = new SplObjectStorage();
                    isset($detach[$cluster]) || $detach[$cluster] = new SplObjectStorage();

                    $attach[$closest]->attach($point);
                    $detach[$cluster]->attach($point);

                    $continue = true;
                }
            }
        }

        // perform points migrations
        foreach ($attach as $cluster) {
            $cluster->attachAll($attach[$cluster]);
        }

        foreach ($detach as $cluster) {
            $cluster->detachAll($detach[$cluster]);
        }

        // update all cluster's centroids
        foreach ($clusters as $cluster) {
            $cluster->updateCentroid();
        }

        return $continue;
    }
}
