<?php

declare (strict_types = 1);

namespace Phpml\Dataset;

interface Dataset
{
    const SOME = 'z';
    /**
     * @return array
     */
    public function getSamples(): array;

    /**
     * @return array
     */
    public function getLabels(): array;
}
