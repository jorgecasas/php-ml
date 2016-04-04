<?php
declare(strict_types=1);

namespace Phpml\Classifier;

class KNearestNeighbors implements Classifier
{
    /**
     *
     * @var int
     */
    private $k;

    /**
     * @var array
     */
    private $features;

    /**
     * @var array
     */
    private $labels;

    /**
     * @param int $k
     */
    public function __construct(int $k = 3)
    {
        $this->k = $k;
        $this->features = [];
        $this->labels = [];
    }


    /**
     * @param array $features
     * @param array $labels
     */
    public function train(array $features, array $labels)
    {
        $this->features = $features;
        $this->labels = $labels;
    }

    /**
     * @param mixed $feature
     * @return mixed
     */
    public function predict($feature)
    {

    }

}
