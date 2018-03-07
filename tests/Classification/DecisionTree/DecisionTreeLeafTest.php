<?php

declare(strict_types=1);

namespace Phpml\Tests\Classification\DecisionTree;

use Phpml\Classification\DecisionTree\DecisionTreeLeaf;
use PHPUnit\Framework\TestCase;

class DecisionTreeLeafTest extends TestCase
{
    public function testHTMLOutput(): void
    {
        $leaf = new DecisionTreeLeaf();
        $leaf->value = 1;
        $leaf->columnIndex = 0;

        $rightLeaf = new DecisionTreeLeaf();
        $rightLeaf->value = '<= 2';
        $rightLeaf->columnIndex = 1;

        $leaf->rightLeaf = $rightLeaf;

        $this->assertEquals('<table ><tr><td colspan=3 align=center style=\'border:1px solid;\'><b>col_0 =1</b><br>Gini: 0.00</td></tr><tr><td></td><td>&nbsp;</td><td valign=top align=right><b>No |</b><br><table ><tr><td colspan=3 align=center style=\'border:1px solid;\'><b>col_1 <= 2</b><br>Gini: 0.00</td></tr></table></td></tr></table>', $leaf->getHTML());
    }

    public function testNodeImpurityDecreaseShouldBeZeroWhenLeafIsTerminal(): void
    {
        $leaf = new DecisionTreeLeaf();
        $leaf->isTerminal = true;

        $this->assertEquals(0.0, $leaf->getNodeImpurityDecrease(1));
    }

    public function testNodeImpurityDecrease(): void
    {
        $leaf = new DecisionTreeLeaf();
        $leaf->giniIndex = 0.5;
        $leaf->records = [1, 2, 3];

        $leaf->leftLeaf = new DecisionTreeLeaf();
        $leaf->leftLeaf->records = [5, 2];

        $leaf->rightLeaf = new DecisionTreeLeaf();
        $leaf->rightLeaf->records = [];
        $leaf->rightLeaf->giniIndex = 0.3;

        $this->assertSame(0.75, $leaf->getNodeImpurityDecrease(2));
    }
}
