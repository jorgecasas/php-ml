<?php

declare(strict_types=1);

namespace Phpml\Math;

use Phpml\Exception\InvalidArgumentException;

class Comparison
{
    /**
     * @param mixed  $a
     * @param mixed  $b
     * @param string $operator
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function compare($a, $b, string $operator): bool
    {
        switch ($operator) {
            case '>':
                return $a > $b;
            case '>=':
                return $a >= $b;
            case '=':
            case '==':
                return $a == $b;
            case '===':
                return $a === $b;
            case '<=':
                return $a <= $b;
            case '<':
                return $a < $b;
            case '!=':
            case '<>':
                return $a != $b;
            case '!==':
                return $a !== $b;
            default:
                throw InvalidArgumentException::invalidOperator($operator);
        }
    }
}
