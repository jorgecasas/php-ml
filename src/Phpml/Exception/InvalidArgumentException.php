<?php

declare(strict_types=1);

namespace Phpml\Exception;

class InvalidArgumentException extends \Exception
{
    public static function arraySizeNotMatch() : InvalidArgumentException
    {
        return new self('Size of given arrays does not match');
    }

    public static function percentNotInRange($name) : InvalidArgumentException
    {
        return new self(sprintf('%s must be between 0.0 and 1.0', $name));
    }

    public static function arrayCantBeEmpty() : InvalidArgumentException
    {
        return new self('The array has zero elements');
    }

    public static function arraySizeToSmall(int $minimumSize = 2) : InvalidArgumentException
    {
        return new self(sprintf('The array must have at least %d elements', $minimumSize));
    }

    public static function matrixDimensionsDidNotMatch() : InvalidArgumentException
    {
        return new self('Matrix dimensions did not match');
    }

    public static function inconsistentMatrixSupplied() : InvalidArgumentException
    {
        return new self('Inconsistent matrix supplied');
    }

    public static function invalidClustersNumber() : InvalidArgumentException
    {
        return new self('Invalid clusters number');
    }

    /**
     * @param mixed $target
     */
    public static function invalidTarget($target) : InvalidArgumentException
    {
        return new self(sprintf('Target with value "%s" is not part of the accepted classes', $target));
    }

    public static function invalidStopWordsLanguage(string $language) : InvalidArgumentException
    {
        return new self(sprintf('Can\'t find "%s" language for StopWords', $language));
    }

    public static function invalidLayerNodeClass() : InvalidArgumentException
    {
        return new self('Layer node class must implement Node interface');
    }

    public static function invalidLayersNumber() : InvalidArgumentException
    {
        return new self('Provide at least 1 hidden layer');
    }

    public static function invalidClassesNumber() : InvalidArgumentException
    {
        return new self('Provide at least 2 different classes');
    }

    public static function inconsistentClasses() : InvalidArgumentException
    {
        return new self('The provided classes don\'t match the classes provided in the constructor');
    }

    public static function fileNotFound(string $file) : InvalidArgumentException
    {
        return new self(sprintf('File "%s" not found', $file));
    }

    public static function fileNotExecutable(string $file) : InvalidArgumentException
    {
        return new self(sprintf('File "%s" is not executable', $file));
    }

    public static function pathNotFound(string $path) : InvalidArgumentException
    {
        return new self(sprintf('The specified path "%s" does not exist', $path));
    }

    public static function pathNotWritable(string $path) : InvalidArgumentException
    {
        return new self(sprintf('The specified path "%s" is not writable', $path));
    }

    public static function invalidOperator(string $operator) : InvalidArgumentException
    {
        return new self(sprintf('Invalid operator "%s" provided', $operator));
    }
}
