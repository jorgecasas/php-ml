<?php

declare(strict_types=1);

namespace Phpml\Tests\Preprocessing;

use Phpml\Preprocessing\NumberConverter;
use PHPUnit\Framework\TestCase;

final class NumberConverterTest extends TestCase
{
    public function testConvertSamples(): void
    {
        $samples = [['1', '-4'], ['2.0', 3.0], ['3', '112.5'], ['5', '0.0004']];
        $targets = ['1', '1', '2', '2'];

        $converter = new NumberConverter();
        $converter->transform($samples, $targets);

        self::assertEquals([[1.0, -4.0], [2.0, 3.0], [3.0, 112.5], [5.0, 0.0004]], $samples);
        self::assertEquals(['1', '1', '2', '2'], $targets);
    }

    public function testConvertTargets(): void
    {
        $samples = [['1', '-4'], ['2.0', 3.0], ['3', '112.5'], ['5', '0.0004']];
        $targets = ['1', '1', '2', 'not'];

        $converter = new NumberConverter(true);
        $converter->transform($samples, $targets);

        self::assertEquals([[1.0, -4.0], [2.0, 3.0], [3.0, 112.5], [5.0, 0.0004]], $samples);
        self::assertEquals([1.0, 1.0, 2.0, null], $targets);
    }

    public function testConvertWithPlaceholder(): void
    {
        $samples = [['invalid'], ['13.5']];
        $targets = ['invalid', '2'];

        $converter = new NumberConverter(true, 'missing');
        $converter->transform($samples, $targets);

        self::assertEquals([['missing'], [13.5]], $samples);
        self::assertEquals(['missing', 2.0], $targets);
    }
}
