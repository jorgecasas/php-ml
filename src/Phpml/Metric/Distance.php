<?php

declare (strict_types = 1);

namespace Phpml\Metric;

use Phpml\Exception\InvalidArgumentException;

class Distance
{
    /**
     * @param array $a
     * @param array $b
     *
     * @return float
     *
     * @throws InvalidArgumentException
     */
    public static function euclidean(array $a, array $b): float
    {
        if (count($a) != count($b)) {
            throw InvalidArgumentException::sizeNotMatch();
        }

        $distance = 0;
        $count = count($a);

        for ($i = 0; $i < $count; ++$i) {
            $distance += pow($a[$i] - $b[$i], 2);
        }

        return sqrt($distance);
    }
}
