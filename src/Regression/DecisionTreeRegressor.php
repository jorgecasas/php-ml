<?php

declare(strict_types=1);

namespace Phpml\Regression;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Exception\InvalidOperationException;
use Phpml\Math\Statistic\Mean;
use Phpml\Math\Statistic\Variance;
use Phpml\Tree\CART;
use Phpml\Tree\Node\AverageNode;
use Phpml\Tree\Node\BinaryNode;
use Phpml\Tree\Node\DecisionNode;

final class DecisionTreeRegressor extends CART implements Regression
{
    /**
     * @var int|null
     */
    protected $maxFeatures;

    /**
     * @var float
     */
    protected $tolerance;

    /**
     * @var array
     */
    protected $columns = [];

    public function __construct(
        int $maxDepth = PHP_INT_MAX,
        int $maxLeafSize = 3,
        float $minPurityIncrease = 0.,
        ?int $maxFeatures = null,
        float $tolerance = 1e-4
    ) {
        if ($maxFeatures !== null && $maxFeatures < 1) {
            throw new InvalidArgumentException('Max features must be greater than 0');
        }

        if ($tolerance < 0.) {
            throw new InvalidArgumentException('Tolerance must be equal or greater than 0');
        }

        $this->maxFeatures = $maxFeatures;
        $this->tolerance = $tolerance;

        parent::__construct($maxDepth, $maxLeafSize, $minPurityIncrease);
    }

    public function train(array $samples, array $targets): void
    {
        $features = count($samples[0]);

        $this->columns = range(0, $features - 1);
        $this->maxFeatures = $this->maxFeatures ?? (int) round(sqrt($features));

        $this->grow($samples, $targets);

        $this->columns = [];
    }

    public function predict(array $samples)
    {
        if ($this->bare()) {
            throw new InvalidOperationException('Regressor must be trained first');
        }

        $predictions = [];

        foreach ($samples as $sample) {
            $node = $this->search($sample);

            $predictions[] = $node instanceof AverageNode
                ? $node->outcome()
                : null;
        }

        return $predictions;
    }

    protected function split(array $samples, array $targets): DecisionNode
    {
        $bestVariance = INF;
        $bestColumn = $bestValue = null;
        $bestGroups = [];

        shuffle($this->columns);

        foreach (array_slice($this->columns, 0, $this->maxFeatures) as $column) {
            $values = array_unique(array_column($samples, $column));

            foreach ($values as $value) {
                $groups = $this->partition($column, $value, $samples, $targets);

                $variance = $this->splitImpurity($groups);

                if ($variance < $bestVariance) {
                    $bestColumn = $column;
                    $bestValue = $value;
                    $bestGroups = $groups;
                    $bestVariance = $variance;
                }

                if ($variance <= $this->tolerance) {
                    break 2;
                }
            }
        }

        return new DecisionNode($bestColumn, $bestValue, $bestGroups, $bestVariance);
    }

    protected function terminate(array $targets): BinaryNode
    {
        return new AverageNode(Mean::arithmetic($targets), Variance::population($targets), count($targets));
    }

    protected function splitImpurity(array $groups): float
    {
        $samplesCount = (int) array_sum(array_map(static function (array $group) {
            return count($group[0]);
        }, $groups));

        $impurity = 0.;

        foreach ($groups as $group) {
            $k = count($group[1]);

            if ($k < 2) {
                continue 1;
            }

            $variance = Variance::population($group[1]);

            $impurity += ($k / $samplesCount) * $variance;
        }

        return $impurity;
    }

    /**
     * @param int|float $value
     */
    private function partition(int $column, $value, array $samples, array $targets): array
    {
        $leftSamples = $leftTargets = $rightSamples = $rightTargets = [];
        foreach ($samples as $index => $sample) {
            if ($sample[$column] < $value) {
                $leftSamples[] = $sample;
                $leftTargets[] = $targets[$index];
            } else {
                $rightSamples[] = $sample;
                $rightTargets[] = $targets[$index];
            }
        }

        return [
            [$leftSamples, $leftTargets],
            [$rightSamples, $rightTargets],
        ];
    }
}
