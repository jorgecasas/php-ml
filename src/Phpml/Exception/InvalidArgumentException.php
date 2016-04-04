<?php
declare(strict_types = 1);

namespace Phpml\Exception;

class InvalidArgumentException extends \Exception
{

    /**
     * @return InvalidArgumentException
     */
    public static function parametersSizeNotMatch()
    {
        return new self('Size of parameters not match');
    }

}
