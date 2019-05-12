<?php

declare(strict_types=1);

namespace Phpml\Tree;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Tree\Node\BinaryNode;
use Phpml\Tree\Node\DecisionNode;
use Phpml\Tree\Node\LeafNode;

abstract class CART
{
    /**
     * @var DecisionNode|null
     */
    protected $root;

    /**
     * @var int
     */
    protected $maxDepth;

    /**
     * @var int
     */
    protected $maxLeafSize;

    /**
     * @var float
     */
    protected $minPurityIncrease;

    /**
     * @var int
     */
    protected $featureCount;

    public function __construct(int $maxDepth = PHP_INT_MAX, int $maxLeafSize = 3, float $minPurityIncrease = 0.)
    {
        if ($maxDepth < 1) {
            throw new InvalidArgumentException('Max depth must be greater than 0');
        }

        if ($maxLeafSize < 1) {
            throw new InvalidArgumentException('Max leaf size must be greater than 0');
        }

        if ($minPurityIncrease < 0.) {
            throw new InvalidArgumentException('Min purity increase must be equal or greater than 0');
        }

        $this->maxDepth = $maxDepth;
        $this->maxLeafSize = $maxLeafSize;
        $this->minPurityIncrease = $minPurityIncrease;
    }

    public function root(): ?DecisionNode
    {
        return $this->root;
    }

    public function height(): int
    {
        return $this->root !== null ? $this->root->height() : 0;
    }

    public function balance(): int
    {
        return $this->root !== null ? $this->root->balance() : 0;
    }

    public function bare(): bool
    {
        return $this->root === null;
    }

    public function grow(array $samples, array $targets): void
    {
        $this->featureCount = count($samples[0]);
        $depth = 1;
        $this->root = $this->split($samples, $targets);
        $stack = [[$this->root, $depth]];

        while ($stack) {
            [$current, $depth] = array_pop($stack) ?? [];

            [$left, $right] = $current->groups();

            $current->cleanup();

            $depth++;

            if ($left[1] === [] || $right[1] === []) {
                $node = $this->terminate(array_merge($left[1], $right[1]));

                $current->attachLeft($node);
                $current->attachRight($node);

                continue 1;
            }

            if ($depth >= $this->maxDepth) {
                $current->attachLeft($this->terminate($left[1]));
                $current->attachRight($this->terminate($right[1]));

                continue 1;
            }

            if (count($left[1]) > $this->maxLeafSize) {
                $node = $this->split($left[0], $left[1]);

                if ($node->purityIncrease() + 1e-8 > $this->minPurityIncrease) {
                    $current->attachLeft($node);

                    $stack[] = [$node, $depth];
                } else {
                    $current->attachLeft($this->terminate($left[1]));
                }
            } else {
                $current->attachLeft($this->terminate($left[1]));
            }

            if (count($right[1]) > $this->maxLeafSize) {
                $node = $this->split($right[0], $right[1]);

                if ($node->purityIncrease() + 1e-8 > $this->minPurityIncrease) {
                    $current->attachRight($node);

                    $stack[] = [$node, $depth];
                } else {
                    $current->attachRight($this->terminate($right[1]));
                }
            } else {
                $current->attachRight($this->terminate($right[1]));
            }
        }
    }

    public function search(array $sample): ?BinaryNode
    {
        $current = $this->root;

        while ($current) {
            if ($current instanceof DecisionNode) {
                $value = $current->value();

                if (is_string($value)) {
                    if ($sample[$current->column()] === $value) {
                        $current = $current->left();
                    } else {
                        $current = $current->right();
                    }
                } else {
                    if ($sample[$current->column()] < $value) {
                        $current = $current->left();
                    } else {
                        $current = $current->right();
                    }
                }

                continue 1;
            }

            if ($current instanceof LeafNode) {
                break 1;
            }
        }

        return $current;
    }

    abstract protected function split(array $samples, array $targets): DecisionNode;

    abstract protected function terminate(array $targets): BinaryNode;
}
