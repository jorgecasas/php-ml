<?php

declare(strict_types=1);

namespace Phpml\Preprocessing\Imputer\Strategy;

use Phpml\Math\Statistic\Mean;
use Phpml\Preprocessing\Imputer\Strategy;

class MeanStrategy implements Strategy
{
    /**
     * @param array $currentAxis
     */
    public function replaceValue(array $currentAxis) : float
    {
        return Mean::arithmetic($currentAxis);
    }
}
