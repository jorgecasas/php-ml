<?php

declare(strict_types=1);

namespace Phpml;

use Phpml\Estimator;
use Phpml\Exception\SerializeException;
use Phpml\Exception\FileException;

class ModelManager
{
    /**
     * @param Estimator     $object
     * @param string        $filepath
     */
    public function saveToFile(Estimator $object, string $filepath)
    {
        if (!file_exists($filepath) || !is_writable(dirname($filepath))) {
            throw FileException::cantSaveFile(basename($filepath));
        }

        $serialized = serialize($object);
        if (empty($serialized)) {
            throw SerializeException::cantSerialize(get_type($object));
        }

        $result = file_put_contents($filepath, $serialized, LOCK_EX);
        if ($result === false) {
            throw FileException::cantSaveFile(basename($filepath));
        }
    }

    /**
     * @param string $filepath
     *
     * @return Estimator
     */
    public function restoreFromFile(string $filepath)
    {
        if (!file_exists($filepath) || !is_readable($filepath)) {
            throw FileException::cantOpenFile(basename($filepath));
        }

        $object = unserialize(file_get_contents($filepath));
        if ($object === false) {
            throw SerializeException::cantUnserialize(basename($filepath));
        }

        return $object;
    }
}
