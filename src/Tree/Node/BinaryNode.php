<?php

declare(strict_types=1);

namespace Phpml\Tree\Node;

use Phpml\Tree\Node;

class BinaryNode implements Node
{
    /**
     * @var self|null
     */
    private $parent;

    /**
     * @var self|null
     */
    private $left;

    /**
     * @var self|null
     */
    private $right;

    public function parent(): ?self
    {
        return $this->parent;
    }

    public function left(): ?self
    {
        return $this->left;
    }

    public function right(): ?self
    {
        return $this->right;
    }

    public function height(): int
    {
        return 1 + max($this->left !== null ? $this->left->height() : 0, $this->right !== null ? $this->right->height() : 0);
    }

    public function balance(): int
    {
        return ($this->right !== null ? $this->right->height() : 0) - ($this->left !== null ? $this->left->height() : 0);
    }

    public function setParent(?self $node = null): void
    {
        $this->parent = $node;
    }

    public function attachLeft(self $node): void
    {
        $node->setParent($this);
        $this->left = $node;
    }

    public function detachLeft(): void
    {
        if ($this->left !== null) {
            $this->left->setParent();
            $this->left = null;
        }
    }

    public function attachRight(self $node): void
    {
        $node->setParent($this);
        $this->right = $node;
    }

    public function detachRight(): void
    {
        if ($this->right !== null) {
            $this->right->setParent();
            $this->right = null;
        }
    }
}
