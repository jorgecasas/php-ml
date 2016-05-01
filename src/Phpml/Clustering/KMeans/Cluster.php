<?php

declare(strict_types = 1);

namespace Phpml\Clustering\KMeans;

use \IteratorAggregate;
use \Countable;
use \SplObjectStorage;
use \LogicException;

class Cluster extends Point implements IteratorAggregate, Countable
{
	protected $space;

	/**
	 * @var SplObjectStorage|Point[]
	 */
	protected $points;

	public function __construct(Space $space, array $coordinates)
	{
		parent::__construct($space, $coordinates);
		$this->points = new SplObjectStorage;
	}

	/**
	 * @return array
	 */
	public function getPoints()
	{
		$points = [];
		foreach ($this->points as $point) {
			$points[] = $point->toArray();
		}

		return $points;
	}
	
	public function toArray()
	{
		$points = array();
		foreach ($this->points as $point)
			$points[] = $point->toArray();

		return array(
			'centroid' => parent::toArray(),
			'points'   => $points,
		);
	}

	public function attach(Point $point)
	{
		if ($point instanceof self)
			throw new LogicException("cannot attach a cluster to another");

		$this->points->attach($point);
		return $point;
	}

	public function detach(Point $point)
	{
		$this->points->detach($point);
		return $point;
	}

	public function attachAll(SplObjectStorage $points)
	{
		$this->points->addAll($points);
	}

	public function detachAll(SplObjectStorage $points)
	{
		$this->points->removeAll($points);
	}

	public function updateCentroid()
	{
		if (!$count = count($this->points))
			return;

		$centroid = $this->space->newPoint(array_fill(0, $this->dimention, 0));

		foreach ($this->points as $point)
			for ($n=0; $n<$this->dimention; $n++)
				$centroid->coordinates[$n] += $point->coordinates[$n];

		for ($n=0; $n<$this->dimention; $n++)
			$this->coordinates[$n] = $centroid->coordinates[$n] / $count;
	}

	public function getIterator()
	{
		return $this->points;
	}

	public function count()
	{
		return count($this->points);
	}
}
