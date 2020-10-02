<?php

declare(strict_types=1);

require __DIR__.'/../../vendor/autoload.php';

$dataDir = __DIR__.'/Data';

$datasets = [
    'https://github.com/php-ai/php-ml-datasets/raw/0.1.0/datasets/bike-sharing-hour.csv',
];

foreach ($datasets as $dataset) {
    $path = $dataDir.'/'.basename($dataset);
    if (!file_exists($path)) {
        if (!copy($dataset, $path)) {
            die(sprintf('Failed to download %s', $dataset));
        }
    }
}
