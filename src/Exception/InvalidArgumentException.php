<?php

declare(strict_types=1);

namespace Phpml\Exception;

use Exception;

class InvalidArgumentException extends Exception
{
    public static function arraySizeNotMatch(): self
    {
        return new self('Size of given arrays does not match');
    }

    public static function percentNotInRange($name): self
    {
        return new self(sprintf('%s must be between 0.0 and 1.0', $name));
    }

    public static function arrayCantBeEmpty(): self
    {
        return new self('The array has zero elements');
    }

    public static function arraySizeTooSmall(int $minimumSize = 2): self
    {
        return new self(sprintf('The array must have at least %d elements', $minimumSize));
    }

    public static function matrixDimensionsDidNotMatch(): self
    {
        return new self('Matrix dimensions did not match');
    }

    public static function inconsistentMatrixSupplied(): self
    {
        return new self('Inconsistent matrix supplied');
    }

    public static function invalidClustersNumber(): self
    {
        return new self('Invalid clusters number');
    }

    /**
     * @param mixed $target
     */
    public static function invalidTarget($target): self
    {
        return new self(sprintf('Target with value "%s" is not part of the accepted classes', $target));
    }

    public static function invalidStopWordsLanguage(string $language): self
    {
        return new self(sprintf('Can\'t find "%s" language for StopWords', $language));
    }

    public static function invalidLayerNodeClass(): self
    {
        return new self('Layer node class must implement Node interface');
    }

    public static function invalidLayersNumber(): self
    {
        return new self('Provide at least 1 hidden layer');
    }

    public static function invalidClassesNumber(): self
    {
        return new self('Provide at least 2 different classes');
    }

    public static function inconsistentClasses(): self
    {
        return new self('The provided classes don\'t match the classes provided in the constructor');
    }

    public static function fileNotFound(string $file): self
    {
        return new self(sprintf('File "%s" not found', $file));
    }

    public static function fileNotExecutable(string $file): self
    {
        return new self(sprintf('File "%s" is not executable', $file));
    }

    public static function pathNotFound(string $path): self
    {
        return new self(sprintf('The specified path "%s" does not exist', $path));
    }

    public static function pathNotWritable(string $path): self
    {
        return new self(sprintf('The specified path "%s" is not writable', $path));
    }

    public static function invalidOperator(string $operator): self
    {
        return new self(sprintf('Invalid operator "%s" provided', $operator));
    }
}
