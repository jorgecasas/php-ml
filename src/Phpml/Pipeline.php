<?php

declare (strict_types = 1);

namespace Phpml\Pipeline;

class Pipeline
{
    /**
     * @var array
     */
    private $stages;

    /**
     * @param array $stages
     */
    public function __construct(array $stages)
    {
        $this->stages = $stages;
    }

    /**
     * @param mixed $stage
     */
    public function addStage($stage)
    {
        $this->stages[] = $stage;
    }
}
