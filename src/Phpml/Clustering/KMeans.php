<?php
declare(strict_types = 1);

namespace Phpml\Clustering;

use Phpml\Clustering\KMeans\Space;
use Phpml\Exception\InvalidArgumentException;

class KMeans implements Clusterer
{
    /**
     * @var int
     */
    private $clustersNumber;

    /**
     * @param int $clustersNumber
     *
     * @throws InvalidArgumentException
     */
    public function __construct(int $clustersNumber)
    {
        if($clustersNumber <= 0) {
            throw InvalidArgumentException::invalidClustersNumber();
        }
        
        $this->clustersNumber = $clustersNumber;
    }

    /**
     * @param array $samples
     *
     * @return array
     */
    public function cluster(array $samples)
    {
        $space = new Space(count($samples[0]));
        foreach ($samples as $sample) {
            $space->addPoint($sample);
        }
        
        $clusters = [];
        foreach ($space->solve($this->clustersNumber) as $cluster)
        {
            $clusters[] = $cluster->getPoints();
        }
        
        return $clusters;
    }

}
