<?php

declare(strict_types=1);

namespace Phpml\Exception;

use Exception;

class FileException extends Exception
{
    public static function missingFile(string $filepath): self
    {
        return new self(sprintf('File "%s" missing.', $filepath));
    }

    public static function cantOpenFile(string $filepath): self
    {
        return new self(sprintf('File "%s" can\'t be open.', $filepath));
    }

    public static function cantSaveFile(string $filepath): self
    {
        return new self(sprintf('File "%s" can\'t be saved.', $filepath));
    }
}
