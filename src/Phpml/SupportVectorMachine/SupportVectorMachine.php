<?php

declare (strict_types = 1);

namespace Phpml\SupportVectorMachine;

class SupportVectorMachine
{
    /**
     * @var int
     */
    private $type;

    /**
     * @var int
     */
    private $kernel;

    /**
     * @var float
     */
    private $cost;

    /**
     * @var string
     */
    private $binPath;

    /**
     * @var string
     */
    private $varPath;

    /**
     * @var string
     */
    private $model;

    /**
     * @var array
     */
    private $labels;

    /**
     * @param int   $type
     * @param int   $kernel
     * @param float $cost
     */
    public function __construct(int $type, int $kernel, float $cost)
    {
        $this->type = $type;
        $this->kernel = $kernel;
        $this->cost = $cost;

        $rootPath = realpath(implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), '..', '..', '..'])).DIRECTORY_SEPARATOR;

        $this->binPath = $rootPath.'bin'.DIRECTORY_SEPARATOR.'libsvm'.DIRECTORY_SEPARATOR;
        $this->varPath = $rootPath.'var'.DIRECTORY_SEPARATOR;
    }

    /**
     * @param array $samples
     * @param array $labels
     */
    public function train(array $samples, array $labels)
    {
        $this->labels = $labels;
        $trainingSet = DataTransformer::trainingSet($samples, $labels);
        file_put_contents($trainingSetFileName = $this->varPath.uniqid(), $trainingSet);
        $modelFileName = $trainingSetFileName.'-model';

        $command = sprintf('%ssvm-train -s %s -t %s -c %s %s %s', $this->binPath, $this->type, $this->kernel, $this->cost, $trainingSetFileName, $modelFileName);
        $output = '';
        exec(escapeshellcmd($command), $output);

        $this->model = file_get_contents($modelFileName);

        unlink($trainingSetFileName);
        unlink($modelFileName);
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $samples
     *
     * @return array
     */
    public function predict(array $samples)
    {
        $testSet = DataTransformer::testSet($samples);
        file_put_contents($testSetFileName = $this->varPath.uniqid(), $testSet);
        $modelFileName = $testSetFileName.'-model';
        file_put_contents($modelFileName, $this->model);
        $outputFileName = $testSetFileName.'-output';

        $command = sprintf('%ssvm-predict %s %s %s', $this->binPath, $testSetFileName, $modelFileName, $outputFileName);
        $output = '';
        exec(escapeshellcmd($command), $output);

        $predictions = file_get_contents($outputFileName);

        unlink($testSetFileName);
        unlink($modelFileName);
        unlink($outputFileName);

        return DataTransformer::results($predictions, $this->labels);
    }
}
