<?php

declare(strict_types=1);

namespace Phpml\Exception;

class MatrixException extends \Exception
{
    public static function notSquareMatrix() : MatrixException
    {
        return new self('Matrix is not square matrix');
    }

    public static function columnOutOfRange() : MatrixException
    {
        return new self('Column out of range');
    }

    public static function singularMatrix() : MatrixException
    {
        return new self('Matrix is singular');
    }
}
