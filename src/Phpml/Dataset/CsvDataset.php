<?php

declare (strict_types = 1);

namespace Phpml\Dataset;

use Phpml\Exception\DatasetException;

abstract class CsvDataset implements Dataset
{
    /**
     * @var string
     */
    protected $filepath;

    /**
     * @var array
     */
    private $samples = [];

    /**
     * @var array
     */
    private $lables = [];

    public function __construct()
    {
        $filepath = dirname(__FILE__).'/../../../data/'.$this->filepath;

        if (!file_exists($filepath)) {
            throw DatasetException::missingFile(basename($filepath));
        }

        $row = 0;
        if (($handle = fopen($filepath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                ++$row;
                if ($row == 1) {
                    continue;
                }
                $this->samples[] = array_slice($data, 0, 4);
                $this->lables[] = $data[4];
            }
            fclose($handle);
        } else {
            throw DatasetException::cantOpenFile(basename($filepath));
        }
    }

    /**
     * @return array
     */
    public function getSamples(): array
    {
        return $this->samples;
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        return $this->lables;
    }
}
