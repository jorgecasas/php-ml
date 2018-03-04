<?php

declare(strict_types=1);

namespace Phpml\Tests\Helper\Optimizer;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Helper\Optimizer\Optimizer;
use PHPUnit\Framework\TestCase;

class OptimizerTest extends TestCase
{
    public function testThrowExceptionWithInvalidTheta(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of values in the weights array should be 3');
        /** @var Optimizer $optimizer */
        $optimizer = $this->getMockForAbstractClass(Optimizer::class, [3]);

        $optimizer->setTheta([]);
    }

    public function testSetTheta(): void
    {
        /** @var Optimizer $optimizer */
        $optimizer = $this->getMockForAbstractClass(Optimizer::class, [2]);
        $object = $optimizer->setTheta([0.3, 1]);

        $theta = $this->getObjectAttribute($optimizer, 'theta');

        $this->assertSame($object, $optimizer);
        $this->assertSame([0.3, 1], $theta);
    }
}
