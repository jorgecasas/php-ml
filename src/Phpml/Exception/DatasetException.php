<?php

declare(strict_types=1);

namespace Phpml\Exception;

class DatasetException extends \Exception
{
    /**
     * @return DatasetException
     */
    public static function missingFile($filepath)
    {
        return new self(sprintf('Dataset file "%s" missing.', $filepath));
    }

    /**
     * @return DatasetException
     */
    public static function missingFolder($path)
    {
        return new self(sprintf('Dataset root folder "%s" missing.', $path));
    }

    public static function cantOpenFile($filepath)
    {
        return new self(sprintf('Dataset file "%s" can\'t be open.', $filepath));
    }
}
