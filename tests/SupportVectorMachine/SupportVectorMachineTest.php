<?php

declare(strict_types=1);

namespace Phpml\Tests\SupportVectorMachine;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Exception\InvalidOperationException;
use Phpml\Exception\LibsvmCommandException;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\SupportVectorMachine\SupportVectorMachine;
use Phpml\SupportVectorMachine\Type;
use PHPUnit\Framework\TestCase;

class SupportVectorMachineTest extends TestCase
{
    public function testTrainCSVCModelWithLinearKernel(): void
    {
        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $model =
            'svm_type c_svc
kernel_type linear
nr_class 2
total_sv 2
rho 0
label 0 1
nr_sv 1 1
SV
0.25 1:2 2:4 
-0.25 1:4 2:2 
';

        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::LINEAR, 100.0);
        $svm->train($samples, $labels);

        $this->assertEquals($model, $svm->getModel());
    }

    public function testTrainCSVCModelWithProbabilityEstimate(): void
    {
        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $svm = new SupportVectorMachine(
            Type::C_SVC,
            Kernel::LINEAR,
            100.0,
            0.5,
            3,
            null,
            0.0,
            0.1,
            0.01,
            100,
            true,
            true
        );
        $svm->train($samples, $labels);

        $this->assertContains(PHP_EOL.'probA ', $svm->getModel());
        $this->assertContains(PHP_EOL.'probB ', $svm->getModel());
    }

    public function testPredictSampleWithLinearKernel(): void
    {
        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::LINEAR, 100.0);
        $svm->train($samples, $labels);

        $predictions = $svm->predict([
            [3, 2],
            [2, 3],
            [4, -5],
        ]);

        $this->assertEquals('b', $predictions[0]);
        $this->assertEquals('a', $predictions[1]);
        $this->assertEquals('b', $predictions[2]);
    }

    public function testPredictSampleFromMultipleClassWithRbfKernel(): void
    {
        $samples = [
            [1, 3], [1, 4], [1, 4],
            [3, 1], [4, 1], [4, 2],
            [-3, -1], [-4, -1], [-4, -2],
        ];
        $labels = [
            'a', 'a', 'a',
            'b', 'b', 'b',
            'c', 'c', 'c',
        ];

        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::RBF, 100.0);
        $svm->train($samples, $labels);

        $predictions = $svm->predict([
            [1, 5],
            [4, 3],
            [-4, -3],
        ]);

        $this->assertEquals('a', $predictions[0]);
        $this->assertEquals('b', $predictions[1]);
        $this->assertEquals('c', $predictions[2]);
    }

    public function testPredictProbability(): void
    {
        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $svm = new SupportVectorMachine(
            Type::C_SVC,
            Kernel::LINEAR,
            100.0,
            0.5,
            3,
            null,
            0.0,
            0.1,
            0.01,
            100,
            true,
            true
        );
        $svm->train($samples, $labels);

        $predictions = $svm->predictProbability([
            [3, 2],
            [2, 3],
            [4, -5],
        ]);

        $this->assertTrue($predictions[0]['a'] < $predictions[0]['b']);
        $this->assertTrue($predictions[1]['a'] > $predictions[1]['b']);
        $this->assertTrue($predictions[2]['a'] < $predictions[2]['b']);

        // Should be true because the latter is farther from the decision boundary
        $this->assertTrue($predictions[0]['b'] < $predictions[2]['b']);
    }

    public function testThrowExceptionWhenVarPathIsNotWritable(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is not writable');
        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::RBF);
        $svm->setVarPath('var-path');
    }

    public function testThrowExceptionWhenBinPathDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('does not exist');
        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::RBF);
        $svm->setBinPath('bin-path');
    }

    public function testThrowExceptionWhenFileIsNotFoundInBinPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('not found');
        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::RBF);
        $svm->setBinPath('var');
    }

    public function testThrowExceptionWhenLibsvmFailsDuringTrain(): void
    {
        $this->expectException(LibsvmCommandException::class);
        $this->expectExceptionMessage('ERROR: unknown svm type');

        $svm = new SupportVectorMachine(99, Kernel::RBF);
        $svm->train([], []);
    }

    public function testThrowExceptionWhenLibsvmFailsDuringPredict(): void
    {
        $this->expectException(LibsvmCommandException::class);
        $this->expectExceptionMessage('can\'t open model file');

        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::RBF);
        $svm->predict([1]);
    }

    public function testThrowExceptionWhenPredictProbabilityCalledWithoutProperModel(): void
    {
        $this->expectException(InvalidOperationException::class);
        $this->expectExceptionMessage('Model does not support probabiliy estimates');

        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::LINEAR, 100.0);
        $svm->train($samples, $labels);

        $predictions = $svm->predictProbability([
            [3, 2],
            [2, 3],
            [4, -5],
        ]);
    }
}
