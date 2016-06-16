<?php

declare (strict_types = 1);

namespace Phpml\FeatureExtraction;

use Phpml\Tokenization\Tokenizer;
use Phpml\Transformer;

class TokenCountVectorizer implements Transformer
{
    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var float
     */
    private $minDF;

    /**
     * @var array
     */
    private $vocabulary;

    /**
     * @var array
     */
    private $frequencies;

    /**
     * @param Tokenizer $tokenizer
     * @param float     $minDF
     */
    public function __construct(Tokenizer $tokenizer, float $minDF = 0)
    {
        $this->tokenizer = $tokenizer;
        $this->minDF = $minDF;
        $this->vocabulary = [];
        $this->frequencies = [];
    }

    /**
     * @param array $samples
     */
    public function fit(array $samples)
    {
        $this->buildVocabulary($samples);
    }

    /**
     * @param array $samples
     */
    public function transform(array &$samples)
    {
        foreach ($samples as &$sample) {
            $this->transformSample($sample);
        }

        $this->checkDocumentFrequency($samples);
    }

    /**
     * @return array
     */
    public function getVocabulary()
    {
        return array_flip($this->vocabulary);
    }

    /**
     * @param array $samples
     */
    private function buildVocabulary(array &$samples)
    {
        foreach ($samples as $index => $sample) {
            $tokens = $this->tokenizer->tokenize($sample);
            foreach ($tokens as $token) {
                $this->addTokenToVocabulary($token);
            }
        }
    }

    /**
     * @param string $sample
     */
    private function transformSample(string &$sample)
    {
        $counts = [];
        $tokens = $this->tokenizer->tokenize($sample);

        foreach ($tokens as $token) {
            $index = $this->getTokenIndex($token);
            if (false !== $index) {
                $this->updateFrequency($token);
                if (!isset($counts[$index])) {
                    $counts[$index] = 0;
                }

                ++$counts[$index];
            }
        }

        foreach ($this->vocabulary as $index) {
            if (!isset($counts[$index])) {
                $counts[$index] = 0;
            }
        }

        $sample = $counts;
    }

    /**
     * @param string $token
     *
     * @return int|bool
     */
    private function getTokenIndex(string $token)
    {
        return isset($this->vocabulary[$token]) ? $this->vocabulary[$token] : false;
    }

    /**
     * @param string $token
     */
    private function addTokenToVocabulary(string $token)
    {
        if (!isset($this->vocabulary[$token])) {
            $this->vocabulary[$token] = count($this->vocabulary);
        }
    }

    /**
     * @param string $token
     */
    private function updateFrequency(string $token)
    {
        if (!isset($this->frequencies[$token])) {
            $this->frequencies[$token] = 0;
        }

        ++$this->frequencies[$token];
    }

    /**
     * @param array $samples
     * 
     * @return array
     */
    private function checkDocumentFrequency(array &$samples)
    {
        if ($this->minDF > 0) {
            $beyondMinimum = $this->getBeyondMinimumIndexes(count($samples));
            foreach ($samples as &$sample) {
                $this->resetBeyondMinimum($sample, $beyondMinimum);
            }
        }
    }

    /**
     * @param array $sample
     * @param array $beyondMinimum
     */
    private function resetBeyondMinimum(array &$sample, array $beyondMinimum)
    {
        foreach ($beyondMinimum as $index) {
            $sample[$index] = 0;
        }
    }

    /**
     * @param int $samplesCount
     *
     * @return array
     */
    private function getBeyondMinimumIndexes(int $samplesCount)
    {
        $indexes = [];
        foreach ($this->frequencies as $token => $frequency) {
            if (($frequency / $samplesCount) < $this->minDF) {
                $indexes[] = $this->getTokenIndex($token);
            }
        }

        return $indexes;
    }
}
