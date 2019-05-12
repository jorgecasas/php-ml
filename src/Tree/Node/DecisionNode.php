<?php

declare(strict_types=1);

namespace Phpml\Tree\Node;

use Phpml\Exception\InvalidArgumentException;

class DecisionNode extends BinaryNode implements PurityNode
{
    /**
     * @var int
     */
    private $column;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private $groups = [];

    /**
     * @var float
     */
    private $impurity;

    /**
     * @var int
     */
    private $samplesCount;

    /**
     * @param mixed $value
     */
    public function __construct(int $column, $value, array $groups, float $impurity)
    {
        if (count($groups) !== 2) {
            throw new InvalidArgumentException('The number of groups must be exactly two.');
        }

        if ($impurity < 0.) {
            throw new InvalidArgumentException('Impurity cannot be less than 0.');
        }

        $this->column = $column;
        $this->value = $value;
        $this->groups = $groups;
        $this->impurity = $impurity;
        $this->samplesCount = (int) array_sum(array_map(function (array $group) {
            return count($group[0]);
        }, $groups));
    }

    public function column(): int
    {
        return $this->column;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    public function groups(): array
    {
        return $this->groups;
    }

    public function impurity(): float
    {
        return $this->impurity;
    }

    public function samplesCount(): int
    {
        return $this->samplesCount;
    }

    public function purityIncrease(): float
    {
        $impurity = $this->impurity;

        if ($this->left() instanceof PurityNode) {
            $impurity -= $this->left()->impurity()
                * ($this->left()->samplesCount() / $this->samplesCount);
        }

        if ($this->right() instanceof PurityNode) {
            $impurity -= $this->right()->impurity()
                * ($this->right()->samplesCount() / $this->samplesCount);
        }

        return $impurity;
    }

    public function cleanup(): void
    {
        $this->groups = [[], []];
    }
}
