<?php

declare (strict_types = 1);

namespace Phpml\FeatureExtraction;

use Phpml\Tokenization\Tokenizer;

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
    private $tokens;

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
     *
     * @return array
     */
    public function transform(array $samples): array
    {
        $this->buildVocabulary($samples);

        foreach ($samples as $index => $sample) {
            $samples[$index] = $this->transformSample($index);
        }

        $samples = $this->checkDocumentFrequency($samples);

        return $samples;
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
            $this->tokens[$index] = $tokens;
        }
    }

    /**
     * @param int $index
     *
     * @return array
     */
    private function transformSample(int $index)
    {
        $counts = [];
        $tokens = $this->tokens[$index];

        foreach ($tokens as $token) {
            $index = $this->getTokenIndex($token);
            $this->updateFrequency($token);
            if (!isset($counts[$index])) {
                $counts[$index] = 0;
            }

            ++$counts[$index];
        }

        foreach ($this->vocabulary as $index) {
            if (!isset($counts[$index])) {
                $counts[$index] = 0;
            }
        }

        return $counts;
    }

    /**
     * @param string $token
     *
     * @return int
     */
    private function getTokenIndex(string $token): int
    {
        return $this->vocabulary[$token];
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
    private function checkDocumentFrequency(array $samples)
    {
        if ($this->minDF > 0) {
            $beyondMinimum = $this->getBeyondMinimumIndexes(count($samples));
            foreach ($samples as $index => $sample) {
                $samples[$index] = $this->resetBeyondMinimum($sample, $beyondMinimum);
            }
        }

        return $samples;
    }

    /**
     * @param array $sample
     * @param array $beyondMinimum
     *
     * @return array
     */
    private function resetBeyondMinimum(array $sample, array $beyondMinimum)
    {
        foreach ($beyondMinimum as $index) {
            $sample[$index] = 0;
        }

        return $sample;
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
