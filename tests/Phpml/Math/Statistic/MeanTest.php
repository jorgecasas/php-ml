<?php

declare(strict_types=1);

namespace Phpml\Tests\Math\Statistic;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Math\Statistic\Mean;
use PHPUnit\Framework\TestCase;

class MeanTest extends TestCase
{
    public function testArithmeticThrowExceptionOnEmptyArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Mean::arithmetic([]);
    }

    public function testArithmeticMean(): void
    {
        $delta = 0.01;
        $this->assertEquals(3.5, Mean::arithmetic([2, 5]), '', $delta);
        $this->assertEquals(41.16, Mean::arithmetic([43, 21, 25, 42, 57, 59]), '', $delta);
        $this->assertEquals(1.7, Mean::arithmetic([0.5, 0.5, 1.5, 2.5, 3.5]), '', $delta);
    }

    public function testMedianThrowExceptionOnEmptyArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Mean::median([]);
    }

    public function testMedianOnOddLengthArray(): void
    {
        $numbers = [5, 2, 6, 1, 3];

        $this->assertEquals(3, Mean::median($numbers));
    }

    public function testMedianOnEvenLengthArray(): void
    {
        $numbers = [5, 2, 6, 1, 3, 4];

        $this->assertEquals(3.5, Mean::median($numbers));
    }

    public function testModeThrowExceptionOnEmptyArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Mean::mode([]);
    }

    public function testModeOnArray(): void
    {
        $numbers = [5, 2, 6, 1, 3, 4, 6, 6, 5];

        $this->assertEquals(6, Mean::mode($numbers));
    }
}
