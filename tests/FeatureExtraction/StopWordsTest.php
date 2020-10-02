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

        self::assertTrue($stopWords->isStopWord('lorem'));
        self::assertTrue($stopWords->isStopWord('ipsum'));
        self::assertTrue($stopWords->isStopWord('dolor'));

        self::assertFalse($stopWords->isStopWord('consectetur'));
        self::assertFalse($stopWords->isStopWord('adipiscing'));
        self::assertFalse($stopWords->isStopWord('amet'));
    }

    public function testThrowExceptionOnInvalidLanguage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        StopWords::factory('Lorem');
    }

    public function testEnglishStopWords(): void
    {
        $stopWords = StopWords::factory('English');

        self::assertTrue($stopWords->isStopWord('again'));
        self::assertFalse($stopWords->isStopWord('strategy'));
    }

    public function testPolishStopWords(): void
    {
        $stopWords = StopWords::factory('Polish');

        self::assertTrue($stopWords->isStopWord('wam'));
        self::assertFalse($stopWords->isStopWord('transhumanizm'));
    }

    public function testFrenchStopWords(): void
    {
        $stopWords = StopWords::factory('French');

        self::assertTrue($stopWords->isStopWord('alors'));
        self::assertFalse($stopWords->isStopWord('carte'));
    }
}
