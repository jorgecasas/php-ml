<?php

declare(strict_types=1);

namespace Phpml\Exception;

use Exception;

class DatasetException extends Exception
{
    public static function missingFolder(string $path): self
    {
        return new self(sprintf('Dataset root folder "%s" missing.', $path));
    }
}
