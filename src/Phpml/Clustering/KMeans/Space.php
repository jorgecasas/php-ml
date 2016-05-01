<?php
declare(strict_types = 1);

namespace Phpml\Clustering\KMeans;

use \SplObjectStorage;
use \LogicException;
use \InvalidArgumentException;

class Space extends SplObjectStorage
{
	// Default seeding method, initial cluster centroid are randomly choosen
	const SEED_DEFAULT  = 1;

	// Alternative seeding method by David Arthur and Sergei Vassilvitskii
	// (see http://en.wikipedia.org/wiki/K-means++)
	const SEED_DASV = 2;

	protected $dimention;

	public function __construct($dimention)
	{
		if ($dimention < 1)
			throw new LogicException("a space dimention cannot be null or negative");

		$this->dimention = $dimention;
	}

	public function toArray()
	{
		$points = array();
		foreach ($this as $point)
			$points[] = $point->toArray();

		return array('points' => $points);
	}

	public function newPoint(array $coordinates)
	{
		if (count($coordinates) != $this->dimention)
			throw new LogicException("(" . implode(',', $coordinates) . ") is not a point of this space");

		return new Point($this, $coordinates);
	}

	public function addPoint(array $coordinates, $data = null)
	{
		return $this->attach($this->newPoint($coordinates), $data);
	}

	public function attach($point, $data = null)
	{
		if (!$point instanceof Point)
			throw new InvalidArgumentException("can only attach points to spaces");

		return parent::attach($point, $data);
	}

	public function getDimention()
	{
		return $this->dimention;
	}

	public function getBoundaries()
	{
		if (!count($this))
			return false;

		$min = $this->newPoint(array_fill(0, $this->dimention, null));
		$max = $this->newPoint(array_fill(0, $this->dimention, null));

		foreach ($this as $point) {
			for ($n=0; $n < $this->dimention; $n++) {
				($min[$n] > $point[$n] || $min[$n] === null) && $min[$n] = $point[$n];
				($max[$n] < $point[$n] || $max[$n] === null) && $max[$n] = $point[$n];
			}
		}

		return array($min, $max);
	}

	public function getRandomPoint(Point $min, Point $max)
	{
		$point = $this->newPoint(array_fill(0, $this->dimention, null));

		for ($n=0; $n < $this->dimention; $n++)
			$point[$n] = rand($min[$n], $max[$n]);

		return $point;
	}

	/**
	 * @param $nbClusters
	 * @param int $seed
	 * @param null $iterationCallback
	 * @return array|Cluster[]
	 */
	public function solve($nbClusters, $seed = self::SEED_DEFAULT, $iterationCallback = null)
	{
		if ($iterationCallback && !is_callable($iterationCallback))
			throw new InvalidArgumentException("invalid iteration callback");

		// initialize K clusters
		$clusters = $this->initializeClusters($nbClusters, $seed);

		// there's only one cluster, clusterization has no meaning
		if (count($clusters) == 1)
			return $clusters[0];

		// until convergence is reached
		do {
			$iterationCallback && $iterationCallback($this, $clusters);
		} while ($this->iterate($clusters));

		// clustering is done.
		return $clusters;
	}

	protected function initializeClusters($nbClusters, $seed)
	{
		if ($nbClusters <= 0)
			throw new InvalidArgumentException("invalid clusters number");

		switch ($seed) {
			// the default seeding method chooses completely random centroid
			case self::SEED_DEFAULT:
				// get the space boundaries to avoid placing clusters centroid too far from points
				list($min, $max) = $this->getBoundaries();

				// initialize N clusters with a random point within space boundaries
				for ($n=0; $n<$nbClusters; $n++)
					$clusters[] = new Cluster($this, $this->getRandomPoint($min, $max)->getCoordinates());

				break;

			// the DASV seeding method consists of finding good initial centroids for the clusters
			case self::SEED_DASV:
				// find a random point
				$position = rand(1, count($this));
				for ($i=1, $this->rewind(); $i<$position && $this->valid(); $i++, $this->next());
				$clusters[] = new Cluster($this, $this->current()->getCoordinates());

				// retains the distances between points and their closest clusters
				$distances = new SplObjectStorage;

				// create k clusters
				for ($i=1; $i<$nbClusters; $i++) {
					$sum = 0;

					// for each points, get the distance with the closest centroid already choosen
					foreach ($this as $point) {
						$distance = $point->getDistanceWith($point->getClosest($clusters));
						$sum += $distances[$point] = $distance;
					}

					// choose a new random point using a weighted probability distribution
					$sum = rand(0, $sum);
					foreach ($this as $point) {
						if (($sum -= $distances[$point]) > 0)
							continue;

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

	protected function iterate($clusters)
	{
		$continue = false;

		// migration storages
		$attach = new SplObjectStorage;
		$detach = new SplObjectStorage;

		// calculate proximity amongst points and clusters
		foreach ($clusters as $cluster) {
			foreach ($cluster as $point) {
				// find the closest cluster
				$closest = $point->getClosest($clusters);

				// move the point from its old cluster to its closest
				if ($closest !== $cluster) {
					isset($attach[$closest]) || $attach[$closest] = new SplObjectStorage;
					isset($detach[$cluster]) || $detach[$cluster] = new SplObjectStorage;

					$attach[$closest]->attach($point);
					$detach[$cluster]->attach($point);

					$continue = true;
				}
			}
		}

		// perform points migrations
		foreach ($attach as $cluster)
			$cluster->attachAll($attach[$cluster]);

		foreach ($detach as $cluster)
			$cluster->detachAll($detach[$cluster]);

		// update all cluster's centroids
		foreach ($clusters as $cluster)
			$cluster->updateCentroid();

		return $continue;
	}
}
