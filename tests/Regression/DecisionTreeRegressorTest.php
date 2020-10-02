<?php

declare(strict_types=1);

namespace Phpml\Tests\Regression;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Exception\InvalidOperationException;
use Phpml\ModelManager;
use Phpml\Regression\DecisionTreeRegressor;
use PHPUnit\Framework\TestCase;

class DecisionTreeRegressorTest extends TestCase
{
    public function testPredictSingleFeatureSamples(): void
    {
        $delta = 0.01;

        $samples = [[60], [61], [62], [63], [65]];
        $targets = [3.1, 3.6, 3.8, 4, 4.1];

        $regression = new DecisionTreeRegressor(4);
        $regression->train($samples, $targets);

        self::assertEqualsWithDelta([4.05], $regression->predict([[64]]), $delta);

        $samples = [[9300], [10565], [15000], [15000], [17764], [57000], [65940], [73676], [77006], [93739], [146088], [153260]];
        $targets = [7100, 15500, 4400, 4400, 5900, 4600, 8800, 2000, 2750, 2550,  960, 1025];

        $regression = new DecisionTreeRegressor();
        $regression->train($samples, $targets);

        self::assertEqualsWithDelta([11300.0], $regression->predict([[9300]]), $delta);
        self::assertEqualsWithDelta([5250.0], $regression->predict([[57000]]), $delta);
        self::assertEqualsWithDelta([2433.33], $regression->predict([[77006]]), $delta);
        self::assertEqualsWithDelta([11300.0], $regression->predict([[9300]]), $delta);
        self::assertEqualsWithDelta([992.5], $regression->predict([[153260]]), $delta);
    }

    public function testPreventPredictWhenNotTrained(): void
    {
        $regression = new DecisionTreeRegressor();

        $this->expectException(InvalidOperationException::class);

        $regression->predict([[1]]);
    }

    public function testMaxFeaturesLowerThanOne(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new DecisionTreeRegressor(5, 3, 0.0, 0);
    }

    public function testToleranceSmallerThanZero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new DecisionTreeRegressor(5, 3, 0.0, 20, -1);
    }

    public function testSaveAndRestore(): void
    {
        $samples = [[60], [61], [62], [63], [65]];
        $targets = [3.1, 3.6, 3.8, 4, 4.1];

        $regression = new DecisionTreeRegressor(4);
        $regression->train($samples, $targets);

        $testSamples = [[9300], [10565], [15000]];
        $predicted = $regression->predict($testSamples);

        $filename = 'least-squares-test-'.random_int(100, 999).'-'.uniqid('', false);
        $filepath = (string) tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($regression, $filepath);

        $restoredRegression = $modelManager->restoreFromFile($filepath);
        self::assertEquals($regression, $restoredRegression);
        self::assertEquals($predicted, $restoredRegression->predict($testSamples));
    }
}
