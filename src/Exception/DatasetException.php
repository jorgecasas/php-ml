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

    public static function invalidTarget(string $target): self
    {
        return new self(sprintf('Invalid target "%s".', $target));
    }

    public static function invalidIndex(string $index): self
    {
        return new self(sprintf('Invalid index "%s".', $index));
    }

    public static function invalidValue(string $value): self
    {
        return new self(sprintf('Invalid value "%s".', $value));
    }
}
