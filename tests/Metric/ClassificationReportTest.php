<?php

declare(strict_types=1);

namespace Phpml\Tests\Metric;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Metric\ClassificationReport;
use PHPUnit\Framework\TestCase;

class ClassificationReportTest extends TestCase
{
    public function testClassificationReportGenerateWithStringLabels(): void
    {
        $labels = ['cat', 'ant', 'bird', 'bird', 'bird'];
        $predicted = ['cat', 'cat', 'bird', 'bird', 'ant'];

        $report = new ClassificationReport($labels, $predicted);

        $precision = [
            'cat' => 0.5,
            'ant' => 0.0,
            'bird' => 1.0,
        ];
        $recall = [
            'cat' => 1.0,
            'ant' => 0.0,
            'bird' => 0.67,
        ];
        $f1score = [
            'cat' => 0.67,
            'ant' => 0.0,
            'bird' => 0.80,
        ];
        $support = [
            'cat' => 1,
            'ant' => 1,
            'bird' => 3,
        ];

        // ClassificationReport uses macro-averaging as default
        $average = [
            'precision' => 0.5, // (1/2 + 0 + 1) / 3 = 1/2
            'recall' => 0.56,   // (1 + 0 + 2/3) / 3 = 5/9
            'f1score' => 0.49,  // (2/3 + 0 + 4/5) / 3 = 22/45
        ];

        $this->assertEquals($precision, $report->getPrecision(), '', 0.01);
        $this->assertEquals($recall, $report->getRecall(), '', 0.01);
        $this->assertEquals($f1score, $report->getF1score(), '', 0.01);
        $this->assertEquals($support, $report->getSupport(), '', 0.01);
        $this->assertEquals($average, $report->getAverage(), '', 0.01);
    }

    public function testClassificationReportGenerateWithNumericLabels(): void
    {
        $labels = [0, 1, 2, 2, 2];
        $predicted = [0, 0, 2, 2, 1];

        $report = new ClassificationReport($labels, $predicted);

        $precision = [
            0 => 0.5,
            1 => 0.0,
            2 => 1.0,
        ];
        $recall = [
            0 => 1.0,
            1 => 0.0,
            2 => 0.67,
        ];
        $f1score = [
            0 => 0.67,
            1 => 0.0,
            2 => 0.80,
        ];
        $support = [
            0 => 1,
            1 => 1,
            2 => 3,
        ];
        $average = [
            'precision' => 0.5,
            'recall' => 0.56,
            'f1score' => 0.49,
        ];

        $this->assertEquals($precision, $report->getPrecision(), '', 0.01);
        $this->assertEquals($recall, $report->getRecall(), '', 0.01);
        $this->assertEquals($f1score, $report->getF1score(), '', 0.01);
        $this->assertEquals($support, $report->getSupport(), '', 0.01);
        $this->assertEquals($average, $report->getAverage(), '', 0.01);
    }

    public function testClassificationReportAverageOutOfRange(): void
    {
        $labels = ['cat', 'ant', 'bird', 'bird', 'bird'];
        $predicted = ['cat', 'cat', 'bird', 'bird', 'ant'];

        $this->expectException(InvalidArgumentException::class);
        $report = new ClassificationReport($labels, $predicted, 0);
    }

    public function testClassificationReportMicroAverage(): void
    {
        $labels = ['cat', 'ant', 'bird', 'bird', 'bird'];
        $predicted = ['cat', 'cat', 'bird', 'bird', 'ant'];

        $report = new ClassificationReport($labels, $predicted, ClassificationReport::MICRO_AVERAGE);

        $average = [
            'precision' => 0.6, // TP / (TP + FP) = (1 + 0 + 2) / (2 + 1 + 2) = 3/5
            'recall' => 0.6,    // TP / (TP + FN) = (1 + 0 + 2) / (1 + 1 + 3) = 3/5
            'f1score' => 0.6,   // Harmonic mean of precision and recall
        ];

        $this->assertEquals($average, $report->getAverage(), '', 0.01);
    }

    public function testClassificationReportMacroAverage(): void
    {
        $labels = ['cat', 'ant', 'bird', 'bird', 'bird'];
        $predicted = ['cat', 'cat', 'bird', 'bird', 'ant'];

        $report = new ClassificationReport($labels, $predicted, ClassificationReport::MACRO_AVERAGE);

        $average = [
            'precision' => 0.5, // (1/2 + 0 + 1) / 3 = 1/2
            'recall' => 0.56,   // (1 + 0 + 2/3) / 3 = 5/9
            'f1score' => 0.49,  // (2/3 + 0 + 4/5) / 3 = 22/45
        ];

        $this->assertEquals($average, $report->getAverage(), '', 0.01);
    }

    public function testClassificationReportWeightedAverage(): void
    {
        $labels = ['cat', 'ant', 'bird', 'bird', 'bird'];
        $predicted = ['cat', 'cat', 'bird', 'bird', 'ant'];

        $report = new ClassificationReport($labels, $predicted, ClassificationReport::WEIGHTED_AVERAGE);

        $average = [
            'precision' => 0.7, // (1/2 * 1 + 0 * 1 + 1 * 3) / 5 = 7/10
            'recall' => 0.6,    // (1 * 1 + 0 * 1 + 2/3 * 3) / 5 = 3/5
            'f1score' => 0.61,  // (2/3 * 1 + 0 * 1 + 4/5 * 3) / 5 = 46/75
        ];

        $this->assertEquals($average, $report->getAverage(), '', 0.01);
    }

    public function testPreventDivideByZeroWhenTruePositiveAndFalsePositiveSumEqualsZero(): void
    {
        $labels = [1, 2];
        $predicted = [2, 2];

        $report = new ClassificationReport($labels, $predicted);

        $this->assertEquals([
            1 => 0.0,
            2 => 0.5,
        ], $report->getPrecision(), '', 0.01);
    }

    public function testPreventDivideByZeroWhenTruePositiveAndFalseNegativeSumEqualsZero(): void
    {
        $labels = [2, 2, 1];
        $predicted = [2, 2, 3];

        $report = new ClassificationReport($labels, $predicted);

        $this->assertEquals([
            1 => 0.0,
            2 => 1,
            3 => 0,
        ], $report->getPrecision(), '', 0.01);
    }

    public function testPreventDividedByZeroWhenPredictedLabelsAllNotMatch(): void
    {
        $labels = [1, 2, 3, 4, 5];
        $predicted = [2, 3, 4, 5, 6];

        $report = new ClassificationReport($labels, $predicted);

        $this->assertEquals([
            'precision' => 0,
            'recall' => 0,
            'f1score' => 0,
        ], $report->getAverage(), '', 0.01);
    }

    public function testPreventDividedByZeroWhenLabelsAreEmpty(): void
    {
        $labels = [];
        $predicted = [];

        $report = new ClassificationReport($labels, $predicted);

        $this->assertEquals([
            'precision' => 0,
            'recall' => 0,
            'f1score' => 0,
        ], $report->getAverage(), '', 0.01);
    }
}
