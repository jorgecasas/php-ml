<?php

declare(strict_types=1);

namespace Phpml\Tests\Dataset;

use Phpml\Dataset\SvmDataset;
use Phpml\Exception\DatasetException;
use Phpml\Exception\FileException;
use PHPUnit\Framework\TestCase;

class SvmDatasetTest extends TestCase
{
    public function testSvmDatasetEmpty(): void
    {
        $filePath = self::getFilePath('empty.svm');
        $dataset = new SvmDataset($filePath);

        $expectedSamples = [
        ];
        $expectedTargets = [
        ];

        $this->assertEquals($expectedSamples, $dataset->getSamples());
        $this->assertEquals($expectedTargets, $dataset->getTargets());
    }

    public function testSvmDataset1x1(): void
    {
        $filePath = self::getFilePath('1x1.svm');
        $dataset = new SvmDataset($filePath);

        $expectedSamples = [
            [2.3],
        ];
        $expectedTargets = [
            0,
        ];

        $this->assertEquals($expectedSamples, $dataset->getSamples());
        $this->assertEquals($expectedTargets, $dataset->getTargets());
    }

    public function testSvmDataset3x1(): void
    {
        $filePath = self::getFilePath('3x1.svm');
        $dataset = new SvmDataset($filePath);

        $expectedSamples = [
            [2.3],
            [4.56],
            [78.9],
        ];
        $expectedTargets = [
            1,
            0,
            1,
        ];

        $this->assertEquals($expectedSamples, $dataset->getSamples());
        $this->assertEquals($expectedTargets, $dataset->getTargets());
    }

    public function testSvmDataset3x4(): void
    {
        $filePath = self::getFilePath('3x4.svm');
        $dataset = new SvmDataset($filePath);

        $expectedSamples = [
            [2, 4, 6, 8],
            [3, 5, 7, 9],
            [1.2, 3.4, 5.6, 7.8],
        ];
        $expectedTargets = [
            1,
            2,
            0,
        ];

        $this->assertEquals($expectedSamples, $dataset->getSamples());
        $this->assertEquals($expectedTargets, $dataset->getTargets());
    }

    public function testSvmDatasetSparse(): void
    {
        $filePath = self::getFilePath('sparse.svm');
        $dataset = new SvmDataset($filePath);

        $expectedSamples = [
            [0, 3.45, 0, 0, 0],
            [0, 0, 0, 0, 6.789],
        ];
        $expectedTargets = [
            0,
            1,
        ];

        $this->assertEquals($expectedSamples, $dataset->getSamples());
        $this->assertEquals($expectedTargets, $dataset->getTargets());
    }

    public function testSvmDatasetComments(): void
    {
        $filePath = self::getFilePath('comments.svm');
        $dataset = new SvmDataset($filePath);

        $expectedSamples = [
            [2],
            [34],
        ];
        $expectedTargets = [
            0,
            1,
        ];

        $this->assertEquals($expectedSamples, $dataset->getSamples());
        $this->assertEquals($expectedTargets, $dataset->getTargets());
    }

    public function testSvmDatasetTabs(): void
    {
        $filePath = self::getFilePath('tabs.svm');
        $dataset = new SvmDataset($filePath);

        $expectedSamples = [
            [23, 45],
        ];
        $expectedTargets = [
            1,
        ];

        $this->assertEquals($expectedSamples, $dataset->getSamples());
        $this->assertEquals($expectedTargets, $dataset->getTargets());
    }

    public function testSvmDatasetMissingFile(): void
    {
        $this->expectException(FileException::class);

        $filePath = self::getFilePath('err_file_not_exists.svm');
        $dataset = new SvmDataset($filePath);
    }

    public function testSvmDatasetEmptyLine(): void
    {
        $this->expectException(DatasetException::class);

        $filePath = self::getFilePath('err_empty_line.svm');
        $dataset = new SvmDataset($filePath);
    }

    public function testSvmDatasetNoLabels(): void
    {
        $this->expectException(DatasetException::class);

        $filePath = self::getFilePath('err_no_labels.svm');
        $dataset = new SvmDataset($filePath);
    }

    public function testSvmDatasetStringLabels(): void
    {
        $this->expectException(DatasetException::class);

        $filePath = self::getFilePath('err_string_labels.svm');
        $dataset = new SvmDataset($filePath);
    }

    public function testSvmDatasetInvalidSpaces(): void
    {
        $this->expectException(DatasetException::class);

        $filePath = self::getFilePath('err_invalid_spaces.svm');
        $dataset = new SvmDataset($filePath);
    }

    public function testSvmDatasetStringIndex(): void
    {
        $this->expectException(DatasetException::class);

        $filePath = self::getFilePath('err_string_index.svm');
        $dataset = new SvmDataset($filePath);
    }

    public function testSvmDatasetIndexZero(): void
    {
        $this->expectException(DatasetException::class);

        $filePath = self::getFilePath('err_index_zero.svm');
        $dataset = new SvmDataset($filePath);
    }

    public function testSvmDatasetInvalidValue(): void
    {
        $this->expectException(DatasetException::class);

        $filePath = self::getFilePath('err_invalid_value.svm');
        $dataset = new SvmDataset($filePath);
    }

    public function testSvmDatasetInvalidFeature(): void
    {
        $this->expectException(DatasetException::class);

        $filePath = self::getFilePath('err_invalid_feature.svm');
        $dataset = new SvmDataset($filePath);
    }

    private static function getFilePath(string $baseName): string
    {
        return dirname(__FILE__).'/Resources/svm/'.$baseName;
    }
}
