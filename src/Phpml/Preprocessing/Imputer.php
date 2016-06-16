<?php

declare (strict_types = 1);

namespace Phpml\Preprocessing;

use Phpml\Preprocessing\Imputer\Strategy;

class Imputer implements Preprocessor
{
    const AXIS_COLUMN = 0;
    const AXIS_ROW = 1;

    /**
     * @var mixed
     */
    private $missingValue;

    /**
     * @var Strategy
     */
    private $strategy;

    /**
     * @var int
     */
    private $axis;

    /**
     * @param mixed    $missingValue
     * @param Strategy $strategy
     * @param int      $axis
     */
    public function __construct($missingValue = null, Strategy $strategy, int $axis = self::AXIS_COLUMN)
    {
        $this->missingValue = $missingValue;
        $this->strategy = $strategy;
        $this->axis = $axis;
    }

    /**
     * @param array $samples
     */
    public function transform(array &$samples)
    {
        foreach ($samples as &$sample) {
            $this->preprocessSample($sample, $samples);
        }
    }

    /**
     * @param array $sample
     * @param array $samples
     */
    private function preprocessSample(array &$sample, array $samples)
    {
        foreach ($sample as $column => &$value) {
            if ($value === $this->missingValue) {
                $value = $this->strategy->replaceValue($this->getAxis($column, $sample, $samples));
            }
        }
    }

    /**
     * @param int   $column
     * @param array $currentSample
     * @param array $samples
     * 
     * @return array
     */
    private function getAxis(int $column, array $currentSample, array $samples): array
    {
        if (self::AXIS_ROW === $this->axis) {
            return array_diff($currentSample, [$this->missingValue]);
        }

        $axis = [];
        foreach ($samples as $sample) {
            if ($sample[$column] !== $this->missingValue) {
                $axis[] = $sample[$column];
            }
        }

        return $axis;
    }
}
