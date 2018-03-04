<?php

declare(strict_types=1);

namespace Phpml\Tests\Clustering\KMeans;

use LogicException;
use Phpml\Clustering\KMeans\Cluster;
use Phpml\Clustering\KMeans\Point;
use Phpml\Clustering\KMeans\Space;
use PHPUnit\Framework\TestCase;

class ClusterTest extends TestCase
{
    public function testThrowExceptionWhenAttachingToCluster(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot attach a cluster to another');

        $cluster = new Cluster(new Space(1), []);
        $cluster->attach(clone $cluster);
    }

    public function testToArray(): void
    {
        $cluster = new Cluster(new Space(2), [1, 2]);
        $cluster->attach(new Point([1, 1]));

        $this->assertSame([
            'centroid' => [1, 2],
            'points' => [
                [1, 1],
            ],
        ], $cluster->toArray());
    }

    public function testDetach(): void
    {
        $cluster = new Cluster(new Space(2), []);
        $cluster->attach(new Point([1, 2]));
        $cluster->attach($point = new Point([1, 1]));

        $detachedPoint = $cluster->detach($point);

        $this->assertSame($detachedPoint, $point);
        $this->assertNotContains($point, $cluster->getPoints());
        $this->assertCount(1, $cluster);
    }
}
