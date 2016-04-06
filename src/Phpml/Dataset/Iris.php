<?php

declare (strict_types = 1);

namespace Phpml\Dataset;

/**
 * Classes: 3
 * Samples per class: 50
 * Samples total: 150
 * Features per sample: 4.
 */
class Iris extends CsvDataset
{
    /**
     * @var string
     */
    protected $filepath = 'iris.csv';
}
