<?php

declare (strict_types = 1);

namespace Phpml\Math\Statistic;

use Phpml\Exception\InvalidArgumentException;

class Mean
{
    /**
     * @param array $numbers
     *
     * @return float
     */
    public static function arithmetic(array $numbers)
    {
        return array_sum($numbers) / count($numbers);
    }

    /**
     * @param array $numbers
     *
     * @return float|mixed
     *
     * @throws InvalidArgumentException
     */
    public static function median(array $numbers) {
        $count = count($numbers);
        if (0 == $count) {
            throw InvalidArgumentException::arrayCantBeEmpty();
        }

        $middleIndex = floor($count / 2);
        sort($numbers, SORT_NUMERIC);
        $median = $numbers[$middleIndex];

        if (0 == $count % 2) {
            $median = ($median + $numbers[$middleIndex - 1]) / 2;
        }

        return $median;
    }

}
