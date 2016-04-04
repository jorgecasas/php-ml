<?php

declare (strict_types = 1);

namespace Phpml\Exception;

class InvalidArgumentException extends \Exception
{
    /**
     * @return InvalidArgumentException
     */
    public static function sizeNotMatch()
    {
        return new self('Size of given arguments not match');
    }
}
