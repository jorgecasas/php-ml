<?php

declare(strict_types=1);

namespace Phpml\Tests\Math\Kernel;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Math\Kernel\RBF;
use PHPUnit\Framework\TestCase;

class RBFTest extends TestCase
{
    public function testComputeRBFKernelFunction(): void
    {
        $rbf = new RBF($gamma = 0.001);

        self::assertEquals(1, $rbf->compute([1, 2], [1, 2]));
        self::assertEqualsWithDelta(0.97336, $rbf->compute([1, 2, 3], [4, 5, 6]), $delta = 0.0001);
        self::assertEqualsWithDelta(0.00011, $rbf->compute([4, 5], [1, 100]), $delta = 0.0001);

        $rbf = new RBF($gamma = 0.2);

        self::assertEquals(1, $rbf->compute([1, 2], [1, 2]));
        self::assertEqualsWithDelta(0.00451, $rbf->compute([1, 2, 3], [4, 5, 6]), $delta = 0.0001);
        self::assertEquals(0, $rbf->compute([4, 5], [1, 100]));
    }

    public function testThrowExceptionWhenComputeArgumentIsNotAnArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Arguments of Phpml\\Math\\Kernel\\RBF::compute must be arrays');

        (new RBF(0.1))->compute([0], 1.0);
    }
}
