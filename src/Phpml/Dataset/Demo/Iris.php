<?php

declare (strict_types = 1);

namespace Phpml\Dataset\Demo;

use Phpml\Dataset\CsvDataset;

/**
 * Classes: 3
 * Samples per class: 50
 * Samples total: 150
 * Features per sample: 4.
 */
class Iris extends CsvDataset
{
    /**
     * @param string|null $filepath
     */
    public function __construct(string $filepath = null)
    {
        $filepath = dirname(__FILE__).'/../../../../data/iris.csv';
        parent::__construct($filepath);
    }
}
