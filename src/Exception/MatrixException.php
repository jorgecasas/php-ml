<?php

declare(strict_types=1);

namespace Phpml\Exception;

use Exception;

class MatrixException extends Exception
{
    public static function notSquareMatrix(): self
    {
        return new self('Matrix is not square matrix');
    }

    public static function columnOutOfRange(): self
    {
        return new self('Column out of range');
    }

    public static function singularMatrix(): self
    {
        return new self('Matrix is singular');
    }
}
