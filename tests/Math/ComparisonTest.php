<?php

declare(strict_types=1);

namespace Phpml\Tests\Math;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Math\Comparison;
use PHPUnit\Framework\TestCase;

class ComparisonTest extends TestCase
{
    /**
     * @param mixed $a
     * @param mixed $b
     *
     * @dataProvider provideData
     */
    public function testResult($a, $b, string $operator, bool $expected): void
    {
        $result = Comparison::compare($a, $b, $operator);

        $this->assertEquals($expected, $result);
    }

    public function testThrowExceptionWhenOperatorIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid operator "~=" provided');
        Comparison::compare(1, 1, '~=');
    }

    public function provideData(): array
    {
        return [
            // Greater
            [1, 0, '>', true],
            [1, 1, '>', false],
            [0, 1, '>', false],
            // Greater or equal
            [1, 0, '>=', true],
            [1, 1, '>=', true],
            [0, 1, '>=', false],
            // Equal
            [1,   0,  '=', false],
            [1,   1, '==', true],
            [1, '1',  '=', true],
            [1, '0', '==', false],
            // Identical
            [1,     0, '===', false],
            [1,     1, '===', true],
            [1,   '1', '===', false],
            ['a', 'a', '===', true],
            // Not equal
            [1,   0, '!=', true],
            [1,   1, '<>', false],
            [1, '1', '!=', false],
            [1, '0', '<>', true],
            // Not identical
            [1,   0, '!==', true],
            [1,   1, '!==', false],
            [1, '1', '!==', true],
            [1, '0', '!==', true],
            // Less or equal
            [1, 0, '<=', false],
            [1, 1, '<=', true],
            [0, 1, '<=', true],
            // Less
            [1, 0, '<', false],
            [1, 1, '<', false],
            [0, 1, '<', true],
        ];
    }
}
