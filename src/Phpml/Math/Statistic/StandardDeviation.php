<?php
declare(strict_types = 1);

namespace Phpml\Math\Statistic;

use Phpml\Exception\InvalidArgumentException;

class StandardDeviation
{

    /**
     * @param array|float[] $a
     * @param bool $sample
     *
     * @return float
     *
     * @throws InvalidArgumentException
     */
    public static function population(array $a, $sample = true)
    {
        if(empty($a)) {
            throw InvalidArgumentException::arrayCantBeEmpty();
        }
        
        $n = count($a);

        if ($sample && $n === 1) {
            throw InvalidArgumentException::arraySizeToSmall(2);
        }
        
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = $val - $mean;
            $carry += $d * $d;
        };

        if($sample) {
            --$n;
        }

        return sqrt($carry / $n);
    }

}
