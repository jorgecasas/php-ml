<?php

declare (strict_types = 1);

namespace Phpml\Preprocessing;

interface Preprocessor
{
    /**
     * @param array $samples
     */
    public function preprocess(array &$samples);
}
