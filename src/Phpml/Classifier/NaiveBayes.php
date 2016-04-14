<?php

declare (strict_types = 1);

namespace Phpml\Classifier;

class NaiveBayes implements Classifier
{
    /**
     * @var array
     */
    private $samples;

    /**
     * @var array
     */
    private $labels;

    /**
     * @param array $samples
     * @param array $labels
     */
    public function train(array $samples, array $labels)
    {
        $this->samples = $samples;
        $this->labels = $labels;
    }

    /**
     * @param array $samples
     *
     * @return mixed
     */
    public function predict(array $samples)
    {
        if (!is_array($samples[0])) {
            $predicted = $this->predictSample($samples);
        } else {
            $predicted = [];
            foreach ($samples as $index => $sample) {
                $predicted[$index] = $this->predictSample($sample);
            }
        }

        return $predicted;
    }

    /**
     * @param array $sample
     *
     * @return mixed
     */
    private function predictSample(array $sample)
    {
        $predictions = [];
        foreach ($this->labels as $index => $label) {
            $predictions[$label] = 0;
            foreach ($sample as $token => $count) {
                if (array_key_exists($token, $this->samples[$index])) {
                    $predictions[$label] += $count * $this->samples[$index][$token];
                }
            }
        }

        arsort($predictions, SORT_NUMERIC);
        reset($predictions);

        return key($predictions);
    }
}
