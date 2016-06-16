<?php

declare (strict_types = 1);

namespace Phpml;

interface Transformer
{
    /**
     * @param array $samples
     *
     * @return array
     */
    public function transform(array $samples): array;
}
