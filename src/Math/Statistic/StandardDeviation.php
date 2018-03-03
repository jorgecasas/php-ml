<?php

declare(strict_types=1);

namespace Phpml\Math\Statistic;

use Phpml\Exception\InvalidArgumentException;

class StandardDeviation
{
    /**
     * @param array|float[]|int[] $numbers
     */
    public static function population(array $numbers, bool $sample = true): float
    {
        if (empty($numbers)) {
            throw new InvalidArgumentException('The array has zero elements');
        }

        $n = count($numbers);

        if ($sample && $n === 1) {
            throw new InvalidArgumentException('The array must have at least 2 elements');
        }

        $mean = Mean::arithmetic($numbers);
        $carry = 0.0;
        foreach ($numbers as $val) {
            $carry += ($val - $mean) ** 2;
        }

        if ($sample) {
            --$n;
        }

        return sqrt((float) ($carry / $n));
    }

    /**
     * Sum of squares deviations
     * ∑⟮xᵢ - μ⟯²
     *
     * @param array|float[]|int[] $numbers
     */
    public static function sumOfSquares(array $numbers): float
    {
        if (empty($numbers)) {
            throw new InvalidArgumentException('The array has zero elements');
        }

        $mean = Mean::arithmetic($numbers);

        return array_sum(array_map(
            function ($val) use ($mean) {
                return ($val - $mean) ** 2;
            },
            $numbers
        ));
    }
}
