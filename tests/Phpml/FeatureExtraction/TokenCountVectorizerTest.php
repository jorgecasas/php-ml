<?php

declare (strict_types = 1);

namespace tests\Phpml\FeatureExtraction;

use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;

class TokenCountVectorizerTest extends \PHPUnit_Framework_TestCase
{
    public function testTokenCountVectorizerWithWhitespaceTokenizer()
    {
        $samples = [
            'Lorem ipsum dolor sit amet dolor',
            'Mauris placerat ipsum dolor',
            'Mauris diam eros fringilla diam',
        ];

        $vocabulary = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'Mauris', 'placerat', 'diam', 'eros', 'fringilla'];
        $vector = [
            [0 => 1, 1 => 1, 2 => 2, 3 => 1, 4 => 1],
            [5 => 1, 6 => 1, 1 => 1, 2 => 1],
            [5 => 1, 7 => 2, 8 => 1, 9 => 1],
        ];

        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());

        $this->assertEquals($vector, $vectorizer->transform($samples));
        $this->assertEquals($vocabulary, $vectorizer->getVocabulary());
    }

    public function testMinimumDocumentTokenCountFrequency()
    {
        // word at least in half samples
        $samples = [
            'Lorem ipsum dolor sit amet',
            'Lorem ipsum sit amet',
            'ipsum sit amet',
            'ipsum sit amet',
        ];

        $vocabulary = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet'];
        $vector = [
            [0 => 1, 1 => 1, 3 => 1, 4 => 1],
            [0 => 1, 1 => 1, 3 => 1, 4 => 1],
            [1 => 1, 3 => 1, 4 => 1],
            [1 => 1, 3 => 1, 4 => 1],
        ];

        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer(), 0.5);

        $this->assertEquals($vector, $vectorizer->transform($samples));
        $this->assertEquals($vocabulary, $vectorizer->getVocabulary());

        // word at least in all samples
        $samples = [
            'Lorem ipsum dolor sit amet',
            'Morbi quis lacinia arcu. Sed eu sagittis Lorem',
            'Suspendisse gravida consequat eros Lorem',
        ];

        $vector = [
            [0 => 1],
            [0 => 1],
            [0 => 1],
        ];

        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer(), 1);

        $this->assertEquals($vector, $vectorizer->transform($samples));
    }
}
