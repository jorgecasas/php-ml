<?php

declare(strict_types=1);

namespace Phpml\Metric;

class ClassificationReport
{
    /**
     * @var array
     */
    private $precision = [];

    /**
     * @var array
     */
    private $recall = [];

    /**
     * @var array
     */
    private $f1score = [];

    /**
     * @var array
     */
    private $support = [];

    /**
     * @var array
     */
    private $average = [];

    public function __construct(array $actualLabels, array $predictedLabels)
    {
        $truePositive = $falsePositive = $falseNegative = $this->support = self::getLabelIndexedArray($actualLabels, $predictedLabels);

        foreach ($actualLabels as $index => $actual) {
            $predicted = $predictedLabels[$index];
            ++$this->support[$actual];

            if ($actual === $predicted) {
                ++$truePositive[$actual];
            } else {
                ++$falsePositive[$predicted];
                ++$falseNegative[$actual];
            }
        }

        $this->computeMetrics($truePositive, $falsePositive, $falseNegative);
        $this->computeAverage();
    }

    public function getPrecision() : array
    {
        return $this->precision;
    }

    public function getRecall() : array
    {
        return $this->recall;
    }

    public function getF1score() : array
    {
        return $this->f1score;
    }

    public function getSupport() : array
    {
        return $this->support;
    }

    public function getAverage() : array
    {
        return $this->average;
    }

    private function computeMetrics(array $truePositive, array $falsePositive, array $falseNegative): void
    {
        foreach ($truePositive as $label => $tp) {
            $this->precision[$label] = $this->computePrecision($tp, $falsePositive[$label]);
            $this->recall[$label] = $this->computeRecall($tp, $falseNegative[$label]);
            $this->f1score[$label] = $this->computeF1Score((float) $this->precision[$label], (float) $this->recall[$label]);
        }
    }

    private function computeAverage(): void
    {
        foreach (['precision', 'recall', 'f1score'] as $metric) {
            $values = array_filter($this->{$metric});
            if (empty($values)) {
                $this->average[$metric] = 0.0;
                continue;
            }
            $this->average[$metric] = array_sum($values) / count($values);
        }
    }

    /**
     * @return float|string
     */
    private function computePrecision(int $truePositive, int $falsePositive)
    {
        if (0 == ($divider = $truePositive + $falsePositive)) {
            return 0.0;
        }

        return $truePositive / $divider;
    }

    /**
     * @return float|string
     */
    private function computeRecall(int $truePositive, int $falseNegative)
    {
        if (0 == ($divider = $truePositive + $falseNegative)) {
            return 0.0;
        }

        return $truePositive / $divider;
    }

    private function computeF1Score(float $precision, float $recall) : float
    {
        if (0 == ($divider = $precision + $recall)) {
            return 0.0;
        }

        return 2.0 * (($precision * $recall) / $divider);
    }

    private static function getLabelIndexedArray(array $actualLabels, array $predictedLabels) : array
    {
        $labels = array_values(array_unique(array_merge($actualLabels, $predictedLabels)));
        sort($labels);
        $labels = array_combine($labels, array_fill(0, count($labels), 0));

        return $labels;
    }
}
