<?php

declare(strict_types=1);

namespace Phpml\Clustering\KMeans;

use ArrayAccess;

class Point implements ArrayAccess
{
    /**
     * @var int
     */
    protected $dimension;

    /**
     * @var array
     */
    protected $coordinates = [];

    public function __construct(array $coordinates)
    {
        $this->dimension = count($coordinates);
        $this->coordinates = $coordinates;
    }

    public function toArray(): array
    {
        return $this->coordinates;
    }

    /**
     * @return int|mixed
     */
    public function getDistanceWith(self $point, bool $precise = true)
    {
        $distance = 0;
        for ($n = 0; $n < $this->dimension; ++$n) {
            $difference = $this->coordinates[$n] - $point->coordinates[$n];
            $distance += $difference * $difference;
        }

        return $precise ? sqrt((float) $distance) : $distance;
    }

    /**
     * @return mixed
     */
    public function getClosest(array $points)
    {
        $minPoint = null;

        foreach ($points as $point) {
            $distance = $this->getDistanceWith($point, false);

            if (!isset($minDistance)) {
                $minDistance = $distance;
                $minPoint = $point;

                continue;
            }

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $minPoint = $point;
            }
        }

        return $minPoint;
    }

    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->coordinates[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->coordinates[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->coordinates[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->coordinates[$offset]);
    }
}
