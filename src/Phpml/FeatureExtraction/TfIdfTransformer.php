<?php

declare (strict_types = 1);

namespace Phpml\FeatureExtraction;

use Phpml\Transformer;

class TfIdfTransformer implements Transformer
{
    /**
     * @var array
     */
    private $idf;

    /**
     * @param array $samples
     * 
     * @return array
     */
    public function transform(array $samples): array
    {
        $this->countTokensFrequency($samples);

        $count = count($samples);
        foreach ($this->idf as &$value) {
            $value = log($count / $value, 10);
        }

        foreach ($samples as &$sample) {
            foreach ($sample as $index => &$feature) {
                $feature = $feature * $this->idf[$index];
            }
        }

        return $samples;
    }

    /**
     * @param array $samples
     *
     * @return array
     */
    private function countTokensFrequency(array $samples)
    {
        $this->idf = array_fill_keys(array_keys($samples[0]), 0);

        foreach ($samples as $sample) {
            foreach ($sample as $index => $count) {
                if ($count > 0) {
                    ++$this->idf[$index];
                }
            }
        }
    }
}
