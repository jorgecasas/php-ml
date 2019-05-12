<?php

declare(strict_types=1);

namespace Phpml\Tests\Tree\Node;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Tree\Node\DecisionNode;
use PHPUnit\Framework\TestCase;

final class DecisionNodeTest extends TestCase
{
    public function testSimpleNode(): void
    {
        $node = new DecisionNode(2, 4, [
            [[[1, 2, 3]], [1]],
            [[[2, 3, 4]], [2]],
        ], 400);

        self::assertEquals(2, $node->column());
        self::assertEquals(2, $node->samplesCount());
    }

    public function testImpurityIncrease(): void
    {
        $node = new DecisionNode(2, 4, [
            [[[1, 2, 3]], [1]],
            [[[2, 3, 4]], [2]],
        ], 400);

        $node->attachRight(new DecisionNode(2, 4, [
            [[[1, 2, 3]], [1]],
            [[[2, 3, 4]], [2]],
        ], 200));

        $node->attachLeft(new DecisionNode(2, 4, [
            [[[1, 2, 3]], [1]],
            [[[2, 3, 4]], [2]],
        ], 100));

        self::assertEquals(100, $node->purityIncrease());
    }

    public function testThrowExceptionOnInvalidGroupsCount(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new DecisionNode(2, 3, [], 200);
    }

    public function testThrowExceptionOnInvalidImpurity(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new DecisionNode(2, 3, [[], []], -2);
    }
}
