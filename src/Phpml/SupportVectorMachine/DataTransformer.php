<?php

declare(strict_types=1);

namespace Phpml\SupportVectorMachine;

class DataTransformer
{
    public static function trainingSet(array $samples, array $labels, bool $targets = false): string
    {
        $set = '';
        if (!$targets) {
            $numericLabels = self::numericLabels($labels);
        }

        foreach ($labels as $index => $label) {
            $set .= sprintf('%s %s %s', ($targets ? $label : $numericLabels[$label]), self::sampleRow($samples[$index]), PHP_EOL);
        }

        return $set;
    }

    public static function testSet(array $samples): string
    {
        if (!is_array($samples[0])) {
            $samples = [$samples];
        }

        $set = '';
        foreach ($samples as $sample) {
            $set .= sprintf('0 %s %s', self::sampleRow($sample), PHP_EOL);
        }

        return $set;
    }

    public static function predictions(string $rawPredictions, array $labels) : array
    {
        $numericLabels = self::numericLabels($labels);
        $results = [];
        foreach (explode(PHP_EOL, $rawPredictions) as $result) {
            if (isset($result[0])) {
                $results[] = array_search($result, $numericLabels);
            }
        }

        return $results;
    }

    public static function numericLabels(array $labels) : array
    {
        $numericLabels = [];
        foreach ($labels as $label) {
            if (isset($numericLabels[$label])) {
                continue;
            }

            $numericLabels[$label] = count($numericLabels);
        }

        return $numericLabels;
    }

    private static function sampleRow(array $sample): string
    {
        $row = [];
        foreach ($sample as $index => $feature) {
            $row[] = sprintf('%s:%s', $index + 1, $feature);
        }

        return implode(' ', $row);
    }
}
