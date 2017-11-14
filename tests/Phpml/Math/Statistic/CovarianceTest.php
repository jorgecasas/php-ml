<?php

declare(strict_types=1);

namespace tests\Phpml\Math\Statistic;

use Phpml\Math\Statistic\Covariance;
use Phpml\Math\Statistic\Mean;
use PHPUnit\Framework\TestCase;

class CovarianceTest extends TestCase
{
    public function testSimpleCovariance(): void
    {
        // Acceptable error
        $epsilon = 0.001;

        // First a simple example whose result is known and given in
        // http://www.cs.otago.ac.nz/cosc453/student_tutorials/principal_components.pdf
        $matrix = [
            [0.69, 0.49],
            [-1.31, -1.21],
            [0.39, 0.99],
            [0.09, 0.29],
            [1.29, 1.09],
            [0.49, 0.79],
            [0.19, -0.31],
            [-0.81, -0.81],
            [-0.31, -0.31],
            [-0.71, -1.01],
        ];
        $knownCovariance = [
            [0.616555556, 0.615444444],
            [0.615444444, 0.716555556]];
        $x = array_column($matrix, 0);
        $y = array_column($matrix, 1);

        // Calculate only one covariance value: Cov(x, y)
        $cov1 = Covariance::fromDataset($matrix, 0, 0);
        $this->assertEquals($cov1, $knownCovariance[0][0], '', $epsilon);
        $cov1 = Covariance::fromXYArrays($x, $x);
        $this->assertEquals($cov1, $knownCovariance[0][0], '', $epsilon);

        $cov2 = Covariance::fromDataset($matrix, 0, 1);
        $this->assertEquals($cov2, $knownCovariance[0][1], '', $epsilon);
        $cov2 = Covariance::fromXYArrays($x, $y);
        $this->assertEquals($cov2, $knownCovariance[0][1], '', $epsilon);

        // Second: calculation cov matrix with automatic means for each column
        $covariance = Covariance::covarianceMatrix($matrix);
        $this->assertEquals($knownCovariance, $covariance, '', $epsilon);

        // Thirdly, CovMatrix: Means are precalculated and given to the method
        $x = array_column($matrix, 0);
        $y = array_column($matrix, 1);
        $meanX = Mean::arithmetic($x);
        $meanY = Mean::arithmetic($y);

        $covariance = Covariance::covarianceMatrix($matrix, [$meanX, $meanY]);
        $this->assertEquals($knownCovariance, $covariance, '', $epsilon);
    }
}
