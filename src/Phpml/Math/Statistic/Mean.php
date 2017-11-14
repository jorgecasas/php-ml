<?php

declare(strict_types=1);

namespace Phpml\Math\Statistic;

use Phpml\Exception\InvalidArgumentException;

class Mean
{
    /**
     * @throws InvalidArgumentException
     */
    public static function arithmetic(array $numbers) : float
    {
        self::checkArrayLength($numbers);

        return array_sum($numbers) / count($numbers);
    }

    /**
     * @return float|mixed
     *
     * @throws InvalidArgumentException
     */
    public static function median(array $numbers)
    {
        self::checkArrayLength($numbers);

        $count = count($numbers);
        $middleIndex = (int) floor($count / 2);
        sort($numbers, SORT_NUMERIC);
        $median = $numbers[$middleIndex];

        if (0 === $count % 2) {
            $median = ($median + $numbers[$middleIndex - 1]) / 2;
        }

        return $median;
    }

    /**
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public static function mode(array $numbers)
    {
        self::checkArrayLength($numbers);

        $values = array_count_values($numbers);

        return array_search(max($values), $values);
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function checkArrayLength(array $array): void
    {
        if (empty($array)) {
            throw InvalidArgumentException::arrayCantBeEmpty();
        }
    }
}
