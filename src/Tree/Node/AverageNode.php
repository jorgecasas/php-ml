<?php

declare(strict_types=1);

namespace Phpml\Tree\Node;

class AverageNode extends BinaryNode implements PurityNode, LeafNode
{
    /**
     * @var float
     */
    private $outcome;

    /**
     * @var float
     */
    private $impurity;

    /**
     * @var int
     */
    private $samplesCount;

    public function __construct(float $outcome, float $impurity, int $samplesCount)
    {
        $this->outcome = $outcome;
        $this->impurity = $impurity;
        $this->samplesCount = $samplesCount;
    }

    public function outcome(): float
    {
        return $this->outcome;
    }

    public function impurity(): float
    {
        return $this->impurity;
    }

    public function samplesCount(): int
    {
        return $this->samplesCount;
    }
}
