<?php
declare(strict_types = 1);

namespace Phpml\Exception;

class PreprocessorException extends \Exception
{

    /**
     * @return PreprocessorException
     */
    public static function fitNotAllowed()
    {
        return new self('Fit is not allowed for this preprocessor.');
    }

}