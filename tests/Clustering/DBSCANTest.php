<?php

declare(strict_types=1);

namespace Phpml\Tests\Clustering;

use Phpml\Clustering\DBSCAN;
use PHPUnit\Framework\TestCase;

class DBSCANTest extends TestCase
{
    public function testDBSCANSamplesClustering(): void
    {
        $samples = [[1, 1], [8, 7], [1, 2], [7, 8], [2, 1], [8, 9]];
        $clustered = [
            [[1, 1], [1, 2], [2, 1]],
            [[8, 7], [7, 8], [8, 9]],
        ];

        $dbscan = new DBSCAN($epsilon = 2, $minSamples = 3);

        $this->assertEquals($clustered, $dbscan->cluster($samples));

        $samples = [[1, 1], [6, 6], [1, -1], [5, 6], [-1, -1], [7, 8], [-1, 1], [7, 7]];
        $clustered = [
            [[1, 1], [1, -1], [-1, -1], [-1, 1]],
            [[6, 6], [5, 6], [7, 8], [7, 7]],
        ];

        $dbscan = new DBSCAN($epsilon = 3, $minSamples = 4);

        $this->assertEquals($clustered, $dbscan->cluster($samples));
    }

    public function testDBSCANSamplesClusteringAssociative(): void
    {
        $samples = [
            'a' => [1, 1],
            'b' => [9, 9],
            'c' => [1, 2],
            'd' => [9, 8],
            'e' => [7, 7],
            'f' => [8, 7],
        ];
        $clustered = [
            [
                'a' => [1, 1],
                'c' => [1, 2],
            ],
            [
                'b' => [9, 9],
                'd' => [9, 8],
                'e' => [7, 7],
                'f' => [8, 7],
            ],
        ];

        $dbscan = new DBSCAN($epsilon = 3, $minSamples = 2);

        $this->assertEquals($clustered, $dbscan->cluster($samples));
    }

    public function testClusterEpsilonSmall(): void
    {
        $samples = [[0], [1], [2]];
        $clustered = [
        ];

        $dbscan = new DBSCAN($epsilon = 0.5, $minSamples = 2);

        $this->assertEquals($clustered, $dbscan->cluster($samples));
    }

    public function testClusterEpsilonBoundary(): void
    {
        $samples = [[0], [1], [2]];
        $clustered = [
        ];

        $dbscan = new DBSCAN($epsilon = 1.0, $minSamples = 2);

        $this->assertEquals($clustered, $dbscan->cluster($samples));
    }

    public function testClusterEpsilonLarge(): void
    {
        $samples = [[0], [1], [2]];
        $clustered = [
            [[0], [1], [2]],
        ];

        $dbscan = new DBSCAN($epsilon = 1.5, $minSamples = 2);

        $this->assertEquals($clustered, $dbscan->cluster($samples));
    }
}
