<?php

declare(strict_types=1);

namespace Phpml\Exception;

use Exception;

class SerializeException extends Exception
{
    public static function cantUnserialize(string $filepath): self
    {
        return new self(sprintf('"%s" can not be unserialized.', $filepath));
    }

    public static function cantSerialize(string $classname): self
    {
        return new self(sprintf('Class "%s" can not be serialized.', $classname));
    }
}
