<?php

declare (strict_types = 1);

namespace Phpml\Exception;

class DatasetException extends \Exception
{
    /**
     * @return DatasetException
     */
    public static function missingFile($filepath)
    {
        return new self(sprintf('Dataset file %s missing.', $filepath));
    }

    public static function cantOpenFile($filepath)
    {
        return new self(sprintf('Dataset file %s can\'t be open.', $filepath));
    }

}
