<?php

declare(strict_types=1);

namespace Phpml\Tests;

use Phpml\Exception\FileException;
use Phpml\ModelManager;
use Phpml\Regression\LeastSquares;
use PHPUnit\Framework\TestCase;

class ModelManagerTest extends TestCase
{
    public function testSaveAndRestore(): void
    {
        $filename = uniqid();
        $filepath = sys_get_temp_dir().DIRECTORY_SEPARATOR.$filename;

        $estimator = new LeastSquares();
        $modelManager = new ModelManager();
        $modelManager->saveToFile($estimator, $filepath);

        $restored = $modelManager->restoreFromFile($filepath);
        $this->assertEquals($estimator, $restored);
    }

    public function testRestoreWrongFile(): void
    {
        $this->expectException(FileException::class);
        $filepath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'unexisting';
        $modelManager = new ModelManager();
        $modelManager->restoreFromFile($filepath);
    }
}
