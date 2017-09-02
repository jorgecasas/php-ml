<?php

declare(strict_types=1);

namespace Phpml\Exception;

class InvalidArgumentException extends \Exception
{
    /**
     * @return InvalidArgumentException
     */
    public static function arraySizeNotMatch()
    {
        return new self('Size of given arrays does not match');
    }

    /**
     * @param $name
     *
     * @return InvalidArgumentException
     */
    public static function percentNotInRange($name)
    {
        return new self(sprintf('%s must be between 0.0 and 1.0', $name));
    }

    /**
     * @return InvalidArgumentException
     */
    public static function arrayCantBeEmpty()
    {
        return new self('The array has zero elements');
    }

    /**
     * @param int $minimumSize
     *
     * @return InvalidArgumentException
     */
    public static function arraySizeToSmall($minimumSize = 2)
    {
        return new self(sprintf('The array must have at least %d elements', $minimumSize));
    }

    /**
     * @return InvalidArgumentException
     */
    public static function matrixDimensionsDidNotMatch()
    {
        return new self('Matrix dimensions did not match');
    }

    /**
     * @return InvalidArgumentException
     */
    public static function inconsistentMatrixSupplied()
    {
        return new self('Inconsistent matrix supplied');
    }

    /**
     * @return InvalidArgumentException
     */
    public static function invalidClustersNumber()
    {
        return new self('Invalid clusters number');
    }

    /**
     * @param mixed $target
     *
     * @return InvalidArgumentException
     */
    public static function invalidTarget($target)
    {
        return new self(sprintf('Target with value "%s" is not part of the accepted classes', $target));
    }

    /**
     * @param string $language
     *
     * @return InvalidArgumentException
     */
    public static function invalidStopWordsLanguage(string $language)
    {
        return new self(sprintf('Can\'t find "%s" language for StopWords', $language));
    }

    /**
     * @return InvalidArgumentException
     */
    public static function invalidLayerNodeClass()
    {
        return new self('Layer node class must implement Node interface');
    }

    /**
     * @return InvalidArgumentException
     */
    public static function invalidLayersNumber()
    {
        return new self('Provide at least 1 hidden layer');
    }

    /**
     * @return InvalidArgumentException
     */
    public static function invalidClassesNumber()
    {
        return new self('Provide at least 2 different classes');
    }

    /**
     * @return InvalidArgumentException
     */
    public static function inconsistentClasses()
    {
        return new self('The provided classes don\'t match the classes provided in the constructor');
    }

    /**
     * @param string $file
     *
     * @return InvalidArgumentException
     */
    public static function fileNotFound(string $file)
    {
        return new self(sprintf('File "%s" not found', $file));
    }

    /**
     * @param string $file
     *
     * @return InvalidArgumentException
     */
    public static function fileNotExecutable(string $file)
    {
        return new self(sprintf('File "%s" is not executable', $file));
    }

    /**
     * @param string $path
     *
     * @return InvalidArgumentException
     */
    public static function pathNotFound(string $path)
    {
        return new self(sprintf('The specified path "%s" does not exist', $path));
    }

    /**
     * @param string $path
     *
     * @return InvalidArgumentException
     */
    public static function pathNotWritable(string $path)
    {
        return new self(sprintf('The specified path "%s" is not writable', $path));
    }
}
