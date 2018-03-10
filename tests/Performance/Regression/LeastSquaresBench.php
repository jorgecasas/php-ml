<?php

declare(strict_types=1);

namespace Phpml\Tests\Performance\Regression;

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\OutputTimeUnit;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use Phpml\Dataset\CsvDataset;
use Phpml\Dataset\Dataset;
use Phpml\Regression\LeastSquares;

/**
 * @BeforeMethods({"init"})
 * @OutputTimeUnit("seconds")
 */
final class LeastSquaresBench
{
    /**
     * @var Dataset
     */
    private $dataset;

    public function init(): void
    {
        $this->dataset = new CsvDataset(__DIR__.'/../Data/bike-sharing-hour.csv', 14);
    }

    /**
     * @Revs(1)
     * @Iterations(5)
     */
    public function benchLeastSquaresTrain(): void
    {
        $leastSqueares = new LeastSquares();
        $leastSqueares->train($this->dataset->getSamples(), $this->dataset->getTargets());
    }
}
