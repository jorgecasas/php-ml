<?php

declare (strict_types = 1);

namespace Phpml\Metric\Distance;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Metric\Distance;

class Chebyshev implements Distance
{
    /**
     * @param array $a
     * @param array $b
     *
     * @return float
     *
     * @throws InvalidArgumentException
     */
    public function distance(array $a, array $b): float
    {
        if (count($a) !== count($b)) {
            throw InvalidArgumentException::sizeNotMatch();
        }

        $differences = [];
        $count = count($a);

        for ($i = 0; $i < $count; ++$i) {
            $differences[] = abs($a[$i] - $b[$i]);
        }

        return max($differences);
    }
}
