<?php

declare(strict_types=1);

namespace Phpml\Tests\Clustering;

use Phpml\Clustering\FuzzyCMeans;
use Phpml\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FuzzyCMeansTest extends TestCase
{
    public function testFCMSamplesClustering(): void
    {
        $samples = [[1, 1], [8, 7], [1, 2], [7, 8], [2, 1], [8, 9]];
        $fcm = new FuzzyCMeans(2);
        $clusters = $fcm->cluster($samples);
        self::assertCount(2, $clusters);
        foreach ($samples as $index => $sample) {
            if (in_array($sample, $clusters[0], true) || in_array($sample, $clusters[1], true)) {
                unset($samples[$index]);
            }
        }

        self::assertCount(0, $samples);
    }

    public function testMembershipMatrix(): void
    {
        $fcm = new FuzzyCMeans(2);
        $fcm->cluster([[1, 1], [8, 7], [1, 2], [7, 8], [2, 1], [8, 9]]);

        $clusterCount = 2;
        $sampleCount = 6;
        $matrix = $fcm->getMembershipMatrix();
        self::assertCount($clusterCount, $matrix);
        foreach ($matrix as $row) {
            self::assertCount($sampleCount, $row);
        }

        // Transpose of the matrix
        array_unshift($matrix, null);
        $matrix = array_map(...$matrix);
        // All column totals should be equal to 1 (100% membership)
        foreach ($matrix as $col) {
            self::assertEquals(1, array_sum($col));
        }
    }

    /**
     * @dataProvider invalidClusterNumberProvider
     */
    public function testInvalidClusterNumber(int $clusters): void
    {
        $this->expectException(InvalidArgumentException::class);

        new FuzzyCMeans($clusters);
    }

    public function invalidClusterNumberProvider(): array
    {
        return [[0], [-1]];
    }
}
