<?php

declare (strict_types = 1);

namespace Phpml\Helper;

trait Trainable
{
    /**
     * @var array
     */
    private $samples;

    /**
     * @var array
     */
    private $labels;

    /**
     * @param array $samples
     * @param array $labels
     */
    public function train(array $samples, array $labels)
    {
        $this->samples = $samples;
        $this->labels = $labels;
    }
}
