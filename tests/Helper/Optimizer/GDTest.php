<?php

declare(strict_types=1);

namespace Phpml\Tests\Helper\Optimizer;

use Phpml\Helper\Optimizer\GD;
use PHPUnit\Framework\TestCase;

class GDTest extends TestCase
{
    public function testRunOptimization(): void
    {
        // 200 samples from y = -1 + 2x (i.e. theta = [-1, 2])
        $samples = [];
        $targets = [];
        for ($i = -100; $i <= 100; ++$i) {
            $x = $i / 100;
            $samples[] = [$x];
            $targets[] = -1 + 2 * $x;
        }

        $callback = function ($theta, $sample, $target) {
            $y = $theta[0] + $theta[1] * $sample[0];
            $cost = ($y - $target) ** 2 / 2;
            $grad = $y - $target;

            return [$cost, $grad];
        };

        $optimizer = new GD(1);

        $theta = $optimizer->runOptimization($samples, $targets, $callback);

        $this->assertEquals([-1, 2], $theta, '', 0.1);
    }

    public function testRunOptimization2Dim(): void
    {
        // 100 samples from y = -1 + 2x0 - 3x1 (i.e. theta = [-1, 2, -3])
        $samples = [];
        $targets = [];
        for ($i = 0; $i < 100; ++$i) {
            $x0 = intval($i / 10) / 10;
            $x1 = ($i % 10) / 10;
            $samples[] = [$x0, $x1];
            $targets[] = -1 + 2 * $x0 - 3 * $x1;
        }

        $callback = function ($theta, $sample, $target) {
            $y = $theta[0] + $theta[1] * $sample[0] + $theta[2] * $sample[1];
            $cost = ($y - $target) ** 2 / 2;
            $grad = $y - $target;

            return [$cost, $grad];
        };

        $optimizer = new GD(2);
        $optimizer->setChangeThreshold(1e-6);

        $theta = $optimizer->runOptimization($samples, $targets, $callback);

        $this->assertEquals([-1, 2, -3], $theta, '', 0.1);
    }
}
