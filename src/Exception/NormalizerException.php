<?php

declare(strict_types=1);

namespace Phpml\Exception;

use Exception;

class NormalizerException extends Exception
{
    public static function unknownNorm(): self
    {
        return new self('Unknown norm supplied.');
    }
}
