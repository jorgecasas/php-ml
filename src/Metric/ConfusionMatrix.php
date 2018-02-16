<?php

declare(strict_types=1);

namespace Phpml\Metric;

class ConfusionMatrix
{
    public static function compute(array $actualLabels, array $predictedLabels, array $labels = []): array
    {
        $labels = !empty($labels) ? array_flip($labels) : self::getUniqueLabels($actualLabels);
        $matrix = self::generateMatrixWithZeros($labels);

        foreach ($actualLabels as $index => $actual) {
            $predicted = $predictedLabels[$index];

            if (!isset($labels[$actual]) || !isset($labels[$predicted])) {
                continue;
            }

            if ($predicted === $actual) {
                $row = $column = $labels[$actual];
            } else {
                $row = $labels[$actual];
                $column = $labels[$predicted];
            }

            $matrix[$row][$column] += 1;
        }

        return $matrix;
    }

    private static function generateMatrixWithZeros(array $labels): array
    {
        $count = count($labels);
        $matrix = [];

        for ($i = 0; $i < $count; ++$i) {
            $matrix[$i] = array_fill(0, $count, 0);
        }

        return $matrix;
    }

    private static function getUniqueLabels(array $labels): array
    {
        $labels = array_values(array_unique($labels));
        sort($labels);
        $labels = array_flip($labels);

        return $labels;
    }
}
