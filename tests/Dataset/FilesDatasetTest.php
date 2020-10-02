<?php

declare(strict_types=1);

namespace Phpml\Tests\Dataset;

use Phpml\Dataset\FilesDataset;
use Phpml\Exception\DatasetException;
use PHPUnit\Framework\TestCase;

class FilesDatasetTest extends TestCase
{
    public function testThrowExceptionOnMissingRootFolder(): void
    {
        $this->expectException(DatasetException::class);
        new FilesDataset('some/not/existed/path');
    }

    public function testLoadFilesDatasetWithBBCData(): void
    {
        $rootPath = dirname(__FILE__).'/Resources/bbc';

        $dataset = new FilesDataset($rootPath);

        self::assertCount(50, $dataset->getSamples());
        self::assertCount(50, $dataset->getTargets());

        $targets = ['business', 'entertainment', 'politics', 'sport', 'tech'];
        self::assertEquals($targets, array_values(array_unique($dataset->getTargets())));

        $firstSample = file_get_contents($rootPath.'/business/001.txt');
        self::assertEquals($firstSample, $dataset->getSamples()[0]);

        $firstTarget = 'business';
        self::assertEquals($firstTarget, $dataset->getTargets()[0]);

        $lastSample = file_get_contents($rootPath.'/tech/010.txt');
        self::assertEquals($lastSample, $dataset->getSamples()[49]);

        $lastTarget = 'tech';
        self::assertEquals($lastTarget, $dataset->getTargets()[49]);
    }
}
