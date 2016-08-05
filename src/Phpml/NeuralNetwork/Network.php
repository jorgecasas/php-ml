<?php

declare (strict_types = 1);

namespace Phpml\NeuralNetwork;

interface Network extends Node
{

    /**
     * @param mixed $input
     */
    public function setInput($input);

    /**
     * @return array
     */
    public function getLayers(): array;

}
