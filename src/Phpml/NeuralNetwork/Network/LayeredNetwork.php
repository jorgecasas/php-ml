<?php

declare (strict_types = 1);

namespace Phpml\NeuralNetwork\Network;

use Phpml\NeuralNetwork\Network;

abstract class LayeredNetwork implements Network
{

    /**
     * @return array
     */
    public function getLayers(): array
    {

    }

    /**
     * @return float
     */
    public function getOutput(): float
    {

    }

    /**
     * @param mixed $input
     */
    public function setInput($input)
    {

    }

}
