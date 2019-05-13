<?php

declare(strict_types=1);

namespace Phpml\Tests\Preprocessing;

use Phpml\Preprocessing\LambdaTransformer;
use PHPUnit\Framework\TestCase;

final class LambdaTransformerTest extends TestCase
{
    public function testLambdaSampleTransformation(): void
    {
        $transformer = new LambdaTransformer(static function ($sample): int {
            return $sample[0] + $sample[1];
        });

        $samples = [
            [1, 2],
            [3, 4],
            [5, 6],
        ];

        $transformer->transform($samples);

        self::assertEquals([3, 7, 11], $samples);
    }
}
