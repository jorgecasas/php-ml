<?php

declare (strict_types = 1);

namespace Phpml\Preprocessing\Imputer\Strategy;

use Phpml\Preprocessing\Imputer\Strategy;
use Phpml\Math\Statistic\Mean;

class MeanStrategy implements Strategy
{
    public function replaceValue(array $currentAxis)
    {
        return Mean::arithmetic($currentAxis);
    }
}
