<?php

declare (strict_types = 1);

namespace Phpml\Dataset;

use Phpml\Exception\DatasetException;

abstract class Dataset
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
        $filepath =  dirname(__FILE__) . '/../../../data/' . $this->filepath;

        if(!file_exists($filepath)) {
            throw DatasetException::missingFile(basename($filepath));
        }

        $row = 0;
        if (($handle = fopen($filepath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                if($row==1) {
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
    public function getSamples()
    {
        return $this->samples;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->lables;
    }

}
