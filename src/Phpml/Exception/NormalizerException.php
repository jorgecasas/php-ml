<?php

declare (strict_types = 1);

namespace Phpml\Exception;

class NormalizerException extends \Exception
{
    /**
     * @return NormalizerException
     */
    public static function unknownNorm()
    {
        return new self('Unknown norm supplied.');
    }

    /**
     * @return NormalizerException
     */
    public static function fitNotAllowed()
    {
        return new self('Fit is not allowed for this preprocessor.');
    }

}
