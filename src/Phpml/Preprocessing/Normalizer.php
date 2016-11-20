<?php

declare(strict_types=1);

namespace Phpml\Preprocessing;

use Phpml\Exception\NormalizerException;

class Normalizer implements Preprocessor
{
    const NORM_L1 = 1;
    const NORM_L2 = 2;

    /**
     * @var int
     */
    private $norm;

    /**
     * @param int $norm
     *
     * @throws NormalizerException
     */
    public function __construct(int $norm = self::NORM_L2)
    {
        if (!in_array($norm, [self::NORM_L1, self::NORM_L2])) {
            throw NormalizerException::unknownNorm();
        }

        $this->norm = $norm;
    }

    /**
     * @param array $samples
     */
    public function fit(array $samples)
    {
        // intentionally not implemented
    }

    /**
     * @param array $samples
     */
    public function transform(array &$samples)
    {
        $method = sprintf('normalizeL%s', $this->norm);
        foreach ($samples as &$sample) {
            $this->$method($sample);
        }
    }

    /**
     * @param array $sample
     */
    private function normalizeL1(array &$sample)
    {
        $norm1 = 0;
        foreach ($sample as $feature) {
            $norm1 += abs($feature);
        }

        if (0 == $norm1) {
            $count = count($sample);
            $sample = array_fill(0, $count, 1.0 / $count);
        } else {
            foreach ($sample as &$feature) {
                $feature = $feature / $norm1;
            }
        }
    }

    /**
     * @param array $sample
     */
    private function normalizeL2(array &$sample)
    {
        $norm2 = 0;
        foreach ($sample as $feature) {
            $norm2 += $feature * $feature;
        }
        $norm2 = sqrt(floatval($norm2));

        if (0 == $norm2) {
            $sample = array_fill(0, count($sample), 1);
        } else {
            foreach ($sample as &$feature) {
                $feature = $feature / $norm2;
            }
        }
    }
}
