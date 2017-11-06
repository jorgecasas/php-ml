<?php

declare(strict_types=1);

namespace Phpml\Exception;

class NormalizerException extends \Exception
{
    public static function unknownNorm() : NormalizerException
    {
        return new self('Unknown norm supplied.');
    }
}
