<?php

declare(strict_types=1);

namespace Phpml\Tests\DimensionReduction;

use Phpml\DimensionReduction\KernelPCA;
use PHPUnit\Framework\TestCase;

class KernelPCATest extends TestCase
{
    public function testKernelPCA(): void
    {
        // Acceptable error
        $epsilon = 0.001;

        // A simple example whose result is known beforehand
        $data = [
            [2, 2], [1.5, 1],    [1., 1.5], [1., 1.],
            [2., 1.], [2, 2.5], [2., 3.], [1.5, 3],
            [1., 2.5], [1., 2.7], [1., 3.], [1, 3],
            [1, 2], [1.5, 2],    [1.5, 2.2], [1.3, 1.7],
            [1.7, 1.3], [1.5, 1.5], [1.5, 1.6], [1.6, 2],
            [1.7, 2.1], [1.3, 1.3], [1.3, 2.2], [1.4, 2.4],
        ];
        $transformed = [
            [0.016485613899708], [-0.089805657741674], [-0.088695974245924], [-0.069761503810802],
            [-0.068049558133392], [-0.054702087779187], [-0.063229228729333], [-0.06852813588679],
            [-0.10098315410297], [-0.15617881000654], [-0.21266832077299], [-0.21266832077299],
            [-0.039234518840831], [0.40858295942991], [0.40110375047242], [-0.10555116296691],
            [-0.13128352866095], [-0.20865959471756], [-0.17531601535848], [0.4240660966961],
            [0.36351946685163], [-0.14334173054136], [0.22454914091011], [0.15035027480881], ];

        $kpca = new KernelPCA(KernelPCA::KERNEL_RBF, null, 1, 15);
        $reducedData = $kpca->fit($data);

        // Due to the fact that the sign of values can be flipped
        // during the calculation of eigenValues, we have to compare
        // absolute value of the values
        array_map(function ($val1, $val2) use ($epsilon): void {
            $this->assertEquals(abs($val1), abs($val2), '', $epsilon);
        }, $transformed, $reducedData);

        // Fitted KernelPCA object can also transform an arbitrary sample of the
        // same dimensionality with the original dataset
        $newData = [1.25, 2.25];
        $newTransformed = [0.18956227539216];
        $newTransformed2 = $kpca->transform($newData);
        $this->assertEquals(abs($newTransformed[0]), abs($newTransformed2[0]), '', $epsilon);
    }
}
