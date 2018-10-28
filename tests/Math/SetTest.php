<?php

declare(strict_types=1);

namespace Phpml\Tests\Math;

use Phpml\Math\Set;
use PHPUnit\Framework\TestCase;

class SetTest extends TestCase
{
    public function testUnion(): void
    {
        $union = Set::union(new Set([3, 1]), new Set([3, 2, 2]));

        self::assertEquals(new Set([1, 2, 3]), $union);
        self::assertEquals(3, $union->cardinality());
    }

    public function testIntersection(): void
    {
        $intersection = Set::intersection(new Set(['C', 'A']), new Set(['B', 'C']));

        self::assertEquals(new Set(['C']), $intersection);
        self::assertEquals(1, $intersection->cardinality());
    }

    public function testDifference(): void
    {
        $difference = Set::difference(new Set(['C', 'A', 'B']), new Set(['A']));

        self::assertEquals(new Set(['B', 'C']), $difference);
        self::assertEquals(2, $difference->cardinality());
    }

    public function testPower(): void
    {
        $power = Set::power(new Set(['A', 'B']));

        self::assertEquals([new Set(), new Set(['A']), new Set(['B']), new Set(['A', 'B'])], $power);
        self::assertCount(4, $power);
    }

    public function testCartesian(): void
    {
        $cartesian = Set::cartesian(new Set(['A']), new Set([1, 2]));

        self::assertEquals([new Set(['A', 1]), new Set(['A', 2])], $cartesian);
        self::assertCount(2, $cartesian);
    }

    public function testContains(): void
    {
        $set = new Set(['B', 'A', 2, 1]);

        self::assertTrue($set->contains('B'));
        self::assertTrue($set->containsAll(['A', 'B']));

        self::assertFalse($set->contains('C'));
        self::assertFalse($set->containsAll(['A', 'B', 'C']));
    }

    public function testRemove(): void
    {
        $set = new Set(['B', 'A', 2, 1]);

        self::assertEquals((new Set([1, 2, 2, 2, 'B']))->toArray(), $set->remove('A')->toArray());
    }

    public function testAdd(): void
    {
        $set = new Set(['B', 'A', 2, 1]);
        $set->addAll(['foo', 'bar']);
        self::assertEquals(6, $set->cardinality());
    }

    public function testEmpty(): void
    {
        $set = new Set([1, 2]);
        $set->removeAll([2, 1]);
        self::assertEquals(new Set(), $set);
        self::assertTrue($set->isEmpty());
    }

    public function testToArray(): void
    {
        $set = new Set([1, 2, 2, 3, 'A', false, '', 1.1, -1, -10, 'B']);

        self::assertEquals([-10, '', -1, 'A', 'B', 1, 1.1, 2, 3], $set->toArray());
    }
}
