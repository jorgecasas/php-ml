<?php

declare(strict_types=1);

namespace Phpml\Tests\FeatureExtraction;

use Phpml\Exception\InvalidArgumentException;
use Phpml\FeatureExtraction\StopWords;
use PHPUnit\Framework\TestCase;

class StopWordsTest extends TestCase
{
    public function testCustomStopWords(): void
    {
        $stopWords = new StopWords(['lorem', 'ipsum', 'dolor']);

        $this->assertTrue($stopWords->isStopWord('lorem'));
        $this->assertTrue($stopWords->isStopWord('ipsum'));
        $this->assertTrue($stopWords->isStopWord('dolor'));

        $this->assertFalse($stopWords->isStopWord('consectetur'));
        $this->assertFalse($stopWords->isStopWord('adipiscing'));
        $this->assertFalse($stopWords->isStopWord('amet'));
    }

    public function testThrowExceptionOnInvalidLanguage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        StopWords::factory('Lorem');
    }

    public function testEnglishStopWords(): void
    {
        $stopWords = StopWords::factory('English');

        $this->assertTrue($stopWords->isStopWord('again'));
        $this->assertFalse($stopWords->isStopWord('strategy'));
    }

    public function testPolishStopWords(): void
    {
        $stopWords = StopWords::factory('Polish');

        $this->assertTrue($stopWords->isStopWord('wam'));
        $this->assertFalse($stopWords->isStopWord('transhumanizm'));
    }

    public function testFrenchStopWords(): void
    {
        $stopWords = StopWords::factory('French');

        $this->assertTrue($stopWords->isStopWord('alors'));
        $this->assertFalse($stopWords->isStopWord('carte'));
    }
}
