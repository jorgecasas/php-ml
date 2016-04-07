<?php

declare (strict_types = 1);

namespace Phpml\Dataset;

use Phpml\Exception\DatasetException;

class CsvDataset extends ArrayDataset
{
    /**
     * @var string
     */
    protected $filepath;

    /**
     * @param string|null $filepath
     *
     * @throws DatasetException
     */
    public function __construct(string $filepath = null)
    {
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
                $this->labels[] = $data[4];
            }
            fclose($handle);
        } else {
            throw DatasetException::cantOpenFile(basename($filepath));
        }
    }
}
