<?php

declare(strict_types=1);

namespace Phpml\Tests\Preprocessing;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Preprocessing\OneHotEncoder;
use PHPUnit\Framework\TestCase;

final class OneHotEncoderTest extends TestCase
{
    public function testOneHotEncodingWithoutIgnoreUnknown(): void
    {
        $samples = [
            ['fish', 'New York', 'regression'],
            ['dog', 'New York', 'regression'],
            ['fish', 'Vancouver', 'classification'],
            ['dog', 'Vancouver', 'regression'],
        ];

        $encoder = new OneHotEncoder();
        $encoder->fit($samples);
        $encoder->transform($samples);

        self::assertEquals([
            [1, 0, 1, 0, 1, 0],
            [0, 1, 1, 0, 1, 0],
            [1, 0, 0, 1, 0, 1],
            [0, 1, 0, 1, 1, 0],
        ], $samples);
    }

    public function testThrowExceptionWhenUnknownCategory(): void
    {
        $encoder = new OneHotEncoder();
        $encoder->fit([
            ['fish', 'New York', 'regression'],
            ['dog', 'New York', 'regression'],
            ['fish', 'Vancouver', 'classification'],
            ['dog', 'Vancouver', 'regression'],
        ]);
        $samples = [['fish', 'New York', 'ka boom']];

        $this->expectException(InvalidArgumentException::class);

        $encoder->transform($samples);
    }

    public function testIgnoreMissingCategory(): void
    {
        $encoder = new OneHotEncoder(true);
        $encoder->fit([
            ['fish', 'New York', 'regression'],
            ['dog', 'New York', 'regression'],
            ['fish', 'Vancouver', 'classification'],
            ['dog', 'Vancouver', 'regression'],
        ]);
        $samples = [['ka', 'boom', 'riko']];
        $encoder->transform($samples);

        self::assertEquals([
            [0, 0, 0, 0, 0, 0],
        ], $samples);
    }
}
