<?php

declare(strict_types=1);

namespace Phpml\Tests\Preprocessing;

use Phpml\Preprocessing\LabelEncoder;
use PHPUnit\Framework\TestCase;

final class LabelEncoderTest extends TestCase
{
    /**
     * @dataProvider labelEncoderDataProvider
     */
    public function testFitAndTransform(array $samples, array $transformed): void
    {
        $le = new LabelEncoder();
        $le->fit($samples);
        $le->transform($samples);

        self::assertEquals($transformed, $samples);
    }

    public function labelEncoderDataProvider(): array
    {
        return [
            [['one', 'one', 'two', 'three'], [0, 0, 1, 2]],
            [['one', 1, 'two', 'three'], [0, 1, 2, 3]],
            [['one', null, 'two', 'three'], [0, 1, 2, 3]],
            [['one', 'one', 'one', 'one'], [0, 0, 0, 0]],
            [['one', 'one', 'one', 'one', null, null, 1, 1, 2, 'two'], [0, 0, 0, 0, 1, 1, 2, 2, 3, 4]],
        ];
    }

    public function testResetClassesAfterNextFit(): void
    {
        $samples = ['Shanghai', 'Beijing', 'Karachi'];

        $le = new LabelEncoder();
        $le->fit($samples);

        self::assertEquals(['Shanghai', 'Beijing', 'Karachi'], $le->classes());

        $samples = ['Istanbul', 'Dhaka', 'Tokyo'];

        $le->fit($samples);

        self::assertEquals(['Istanbul', 'Dhaka', 'Tokyo'], $le->classes());
    }

    public function testFitAndTransformFullCycle(): void
    {
        $samples = ['Shanghai', 'Beijing', 'Karachi', 'Beijing', 'Beijing', 'Karachi'];
        $encoded = [0, 1, 2, 1, 1, 2];

        $le = new LabelEncoder();
        $le->fit($samples);

        self::assertEquals(['Shanghai', 'Beijing', 'Karachi'], $le->classes());

        $transformed = $samples;
        $le->transform($transformed);
        self::assertEquals($encoded, $transformed);

        $le->inverseTransform($transformed);
        self::assertEquals($samples, $transformed);
    }
}
