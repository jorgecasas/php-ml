<?php

declare(strict_types=1);

namespace Phpml\Exception;

use Exception;

class LibsvmCommandException extends Exception
{
    public static function failedToRun(string $command): self
    {
        return new self(sprintf('Failed running libsvm command: "%s"', $command));
    }
}
