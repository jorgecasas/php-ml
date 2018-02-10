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
}
