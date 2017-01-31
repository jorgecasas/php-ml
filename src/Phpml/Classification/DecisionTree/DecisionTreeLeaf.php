<?php

declare(strict_types=1);

namespace Phpml\Classification\DecisionTree;

class DecisionTreeLeaf
{
    const OPERATOR_EQ = '=';
    /**
     * @var string
     */
    public $value;

    /**
     * @var int
     */
    public $columnIndex;

    /**
     * @var DecisionTreeLeaf
     */
    public $leftLeaf = null;

    /**
     * @var DecisionTreeLeaf
     */
    public $rightLeaf= null;

    /**
     * @var array
     */
    public $records = [];

    /**
     * Class value represented by the leaf, this value is non-empty
     * only for terminal leaves
     *
     * @var string
     */
    public $classValue = '';

    /**
     * @var bool
     */
    public $isTerminal = false;

    /**
     * @var float
     */
    public $giniIndex = 0;

    /**
     * @var int
     */
    public $level = 0;

    /**
     * @param array $record
     * @return bool
     */
    public function evaluate($record)
    {
        $recordField = $record[$this->columnIndex];
        if (preg_match("/^([<>=]{1,2})\s*(.*)/", $this->value, $matches)) {
            $op = $matches[1];
            $value= floatval($matches[2]);
            $recordField = strval($recordField);
            eval("\$result = $recordField $op $value;");
            return $result;
        }
        return $recordField == $this->value;
    }

    public function __toString()
    {
        if ($this->isTerminal) {
            $value = "<b>$this->classValue</b>";
        } else {
            $value = $this->value;
            $col = "col_$this->columnIndex";
            if (! preg_match("/^[<>=]{1,2}/", $value)) {
                $value = "=$value";
            }
            $value = "<b>$col $value</b><br>Gini: ". number_format($this->giniIndex, 2);
        }
        $str = "<table ><tr><td colspan=3 align=center style='border:1px solid;'>
				$value</td></tr>";
        if ($this->leftLeaf || $this->rightLeaf) {
            $str .='<tr>';
            if ($this->leftLeaf) {
                $str .="<td valign=top><b>| Yes</b><br>$this->leftLeaf</td>";
            } else {
                $str .='<td></td>';
            }
            $str .='<td>&nbsp;</td>';
            if ($this->rightLeaf) {
                $str .="<td valign=top align=right><b>No |</b><br>$this->rightLeaf</td>";
            } else {
                $str .='<td></td>';
            }
            $str .= '</tr>';
        }
        $str .= '</table>';
        return $str;
    }
}
