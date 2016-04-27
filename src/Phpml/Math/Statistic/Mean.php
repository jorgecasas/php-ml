<?php

declare (strict_types = 1);

namespace Phpml\Math\Statistic;

class Mean
{
    /**
     * @param array $a
     *
     * @return float
     */
    public static function arithmetic(array $a)
    {
        return array_sum($a) / count($a);
    }
}
