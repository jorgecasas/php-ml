<?php

declare(strict_types=1);

namespace Phpml\Exception;

class DatasetException extends \Exception
{
    /**
     * @param string $filepath
     *
     * @return DatasetException
     */
    public static function missingFile(string $filepath)
    {
        return new self(sprintf('Dataset file "%s" missing.', $filepath));
    }

    /**
     * @param string $path
     *
     * @return DatasetException
     */
    public static function missingFolder(string $path)
    {
        return new self(sprintf('Dataset root folder "%s" missing.', $path));
    }

    /**
     * @param string $filepath
     *
     * @return DatasetException
     */
    public static function cantOpenFile(string $filepath)
    {
        return new self(sprintf('Dataset file "%s" can\'t be open.', $filepath));
    }
}
