<?php

declare(strict_types=1);

namespace Phpml\Tests\Tree\Node;

use Phpml\Tree\Node\BinaryNode;
use PHPUnit\Framework\TestCase;

final class BinaryNodeTest extends TestCase
{
    public function testSimpleNode(): void
    {
        $node = new BinaryNode();

        self::assertEquals(1, $node->height());
        self::assertEquals(0, $node->balance());
    }

    public function testAttachDetachLeft(): void
    {
        $node = new BinaryNode();
        $node->attachLeft(new BinaryNode());

        self::assertEquals(2, $node->height());
        self::assertEquals(-1, $node->balance());

        $node->detachLeft();

        self::assertEquals(1, $node->height());
        self::assertEquals(0, $node->balance());
    }

    public function testAttachDetachRight(): void
    {
        $node = new BinaryNode();
        $node->attachRight(new BinaryNode());

        self::assertEquals(2, $node->height());
        self::assertEquals(1, $node->balance());

        $node->detachRight();

        self::assertEquals(1, $node->height());
        self::assertEquals(0, $node->balance());
    }
}
