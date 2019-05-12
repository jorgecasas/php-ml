<?php

declare(strict_types=1);

namespace Phpml\Tree\Node;

use Phpml\Tree\Node;

interface PurityNode extends Node
{
    public function impurity(): float;

    public function samplesCount(): int;
}
