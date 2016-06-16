<?php

declare (strict_types = 1);

namespace Phpml;

interface Transformer
{
    /**
     * @param array $samples
     */
    public function transform(array &$samples);
}
