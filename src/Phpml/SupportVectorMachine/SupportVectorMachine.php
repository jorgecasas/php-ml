<?php

declare(strict_types=1);

namespace Phpml\SupportVectorMachine;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Helper\Trainable;

class SupportVectorMachine
{
    use Trainable;

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
     * @var float
     */
    private $nu;

    /**
     * @var int
     */
    private $degree;

    /**
     * @var float
     */
    private $gamma;

    /**
     * @var float
     */
    private $coef0;

    /**
     * @var float
     */
    private $epsilon;

    /**
     * @var float
     */
    private $tolerance;

    /**
     * @var int
     */
    private $cacheSize;

    /**
     * @var bool
     */
    private $shrinking;

    /**
     * @var bool
     */
    private $probabilityEstimates;

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
    private $targets = [];

    public function __construct(
        int $type,
        int $kernel,
        float $cost = 1.0,
        float $nu = 0.5,
        int $degree = 3,
        ?float $gamma = null,
        float $coef0 = 0.0,
        float $epsilon = 0.1,
        float $tolerance = 0.001,
        int $cacheSize = 100,
        bool $shrinking = true,
        bool $probabilityEstimates = false
    ) {
        $this->type = $type;
        $this->kernel = $kernel;
        $this->cost = $cost;
        $this->nu = $nu;
        $this->degree = $degree;
        $this->gamma = $gamma;
        $this->coef0 = $coef0;
        $this->epsilon = $epsilon;
        $this->tolerance = $tolerance;
        $this->cacheSize = $cacheSize;
        $this->shrinking = $shrinking;
        $this->probabilityEstimates = $probabilityEstimates;

        $rootPath = realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', '..'])).DIRECTORY_SEPARATOR;

        $this->binPath = $rootPath.'bin'.DIRECTORY_SEPARATOR.'libsvm'.DIRECTORY_SEPARATOR;
        $this->varPath = $rootPath.'var'.DIRECTORY_SEPARATOR;
    }

    public function setBinPath(string $binPath): void
    {
        $this->ensureDirectorySeparator($binPath);
        $this->verifyBinPath($binPath);

        $this->binPath = $binPath;
    }

    public function setVarPath(string $varPath): void
    {
        if (!is_writable($varPath)) {
            throw InvalidArgumentException::pathNotWritable($varPath);
        }

        $this->ensureDirectorySeparator($varPath);
        $this->varPath = $varPath;
    }

    public function train(array $samples, array $targets): void
    {
        $this->samples = array_merge($this->samples, $samples);
        $this->targets = array_merge($this->targets, $targets);

        $trainingSet = DataTransformer::trainingSet($this->samples, $this->targets, in_array($this->type, [Type::EPSILON_SVR, Type::NU_SVR]));
        file_put_contents($trainingSetFileName = $this->varPath.uniqid('phpml', true), $trainingSet);
        $modelFileName = $trainingSetFileName.'-model';

        $command = $this->buildTrainCommand($trainingSetFileName, $modelFileName);
        $output = '';
        exec(escapeshellcmd($command), $output);

        $this->model = file_get_contents($modelFileName);

        unlink($trainingSetFileName);
        unlink($modelFileName);
    }

    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return array
     */
    public function predict(array $samples)
    {
        $testSet = DataTransformer::testSet($samples);
        file_put_contents($testSetFileName = $this->varPath.uniqid('phpml', true), $testSet);
        file_put_contents($modelFileName = $testSetFileName.'-model', $this->model);
        $outputFileName = $testSetFileName.'-output';

        $command = sprintf('%ssvm-predict%s %s %s %s', $this->binPath, $this->getOSExtension(), $testSetFileName, $modelFileName, $outputFileName);
        $output = '';
        exec(escapeshellcmd($command), $output);

        $predictions = file_get_contents($outputFileName);

        unlink($testSetFileName);
        unlink($modelFileName);
        unlink($outputFileName);

        if (in_array($this->type, [Type::C_SVC, Type::NU_SVC])) {
            $predictions = DataTransformer::predictions($predictions, $this->targets);
        } else {
            $predictions = explode(PHP_EOL, trim($predictions));
        }

        if (!is_array($samples[0])) {
            return $predictions[0];
        }

        return $predictions;
    }

    private function getOSExtension(): string
    {
        $os = strtoupper(substr(PHP_OS, 0, 3));
        if ($os === 'WIN') {
            return '.exe';
        } elseif ($os === 'DAR') {
            return '-osx';
        }

        return '';
    }

    private function buildTrainCommand(string $trainingSetFileName, string $modelFileName): string
    {
        return sprintf(
            '%ssvm-train%s -s %s -t %s -c %s -n %s -d %s%s -r %s -p %s -m %s -e %s -h %d -b %d %s %s',
            $this->binPath,
            $this->getOSExtension(),
            $this->type,
            $this->kernel,
            $this->cost,
            $this->nu,
            $this->degree,
            $this->gamma !== null ? ' -g '.$this->gamma : '',
            $this->coef0,
            $this->epsilon,
            $this->cacheSize,
            $this->tolerance,
            $this->shrinking,
            $this->probabilityEstimates,
            escapeshellarg($trainingSetFileName),
            escapeshellarg($modelFileName)
        );
    }

    private function ensureDirectorySeparator(string &$path): void
    {
        if (substr($path, -1) !== DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }
    }

    private function verifyBinPath(string $path): void
    {
        if (!is_dir($path)) {
            throw InvalidArgumentException::pathNotFound($path);
        }

        $osExtension = $this->getOSExtension();
        foreach (['svm-predict', 'svm-scale', 'svm-train'] as $filename) {
            $filePath = $path.$filename.$osExtension;
            if (!file_exists($filePath)) {
                throw InvalidArgumentException::fileNotFound($filePath);
            }

            if (!is_executable($filePath)) {
                throw InvalidArgumentException::fileNotExecutable($filePath);
            }
        }
    }
}
