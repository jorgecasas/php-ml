<?php
declare(strict_types = 1);

namespace Phpml\Clustering\KMeans;

use \ArrayAccess;
use \LogicException;

class Point implements ArrayAccess
{
	protected $space;
	protected $dimention;
	protected $coordinates;

	public function __construct(Space $space, array $coordinates)
	{
		$this->space       = $space;
		$this->dimention   = $space->getDimention();
		$this->coordinates = $coordinates;
	}

	public function toArray()
	{
		return $this->coordinates;
	}

	public function getDistanceWith(self $point, $precise = true)
	{
		if ($point->space !== $this->space)
			throw new LogicException("can only calculate distances from points in the same space");

		$distance = 0;
		for ($n=0; $n<$this->dimention; $n++) {
			$difference = $this->coordinates[$n] - $point->coordinates[$n];
			$distance  += $difference * $difference;
		}

		return $precise ? sqrt($distance) : $distance;
	}

	public function getClosest($points)
	{
		foreach($points as $point) {
			$distance = $this->getDistanceWith($point, false);

			if (!isset($minDistance)) {
				$minDistance = $distance;
				$minPoint    = $point;
				continue;
			}

			if ($distance < $minDistance) {
				$minDistance = $distance;
				$minPoint    = $point;
			}
		}

		return $minPoint;
	}

	public function belongsTo(Space $space)
	{
		return $this->space === $space;
	}

	public function getSpace()
	{
		return $this->space;
	}

	public function getCoordinates()
	{
		return $this->coordinates;
	}

	public function offsetExists($offset)
	{
		return isset($this->coordinates[$offset]);
	}

	public function offsetGet($offset)
	{
		return $this->coordinates[$offset];
	}

	public function offsetSet($offset, $value)
	{
		$this->coordinates[$offset] = $value;
	}

	public function offsetUnset($offset)
	{
		unset($this->coordinates[$offset]);
	}
}
