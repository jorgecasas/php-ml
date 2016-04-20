<?php
declare(strict_types = 1);

namespace Phpml\Math;

class Product
{

    /**
     * @param array $a
     * @param array $b
     *
     * @return mixed
     */
    public function scalar(array $a, array $b)
    {
        $product = 0;
        foreach ($a as $index => $value) {
            $product += $value * $b[$index];
        }

        return $product;
    }

}
