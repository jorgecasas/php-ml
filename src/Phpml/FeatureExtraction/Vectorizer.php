<?php

declare (strict_types = 1);

namespace Phpml\FeatureExtraction;

interface Vectorizer
{
    /**
     * @param array $samples
     *
     * @return array
     */
    public function transform(array $samples): array;
}
