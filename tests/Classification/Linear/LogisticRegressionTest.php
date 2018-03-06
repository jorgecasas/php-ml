<?php

declare(strict_types=1);

namespace Phpml\Tests\Classification\Linear;

use Phpml\Classification\Linear\LogisticRegression;
use Phpml\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;

class LogisticRegressionTest extends TestCase
{
    public function testConstructorThrowWhenInvalidTrainingType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $classifier = new LogisticRegression(
            500,
            true,
            -1,
            'log',
            'L2'
        );
    }

    public function testConstructorThrowWhenInvalidCost(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $classifier = new LogisticRegression(
            500,
            true,
            LogisticRegression::CONJUGATE_GRAD_TRAINING,
            'invalid',
            'L2'
        );
    }

    public function testConstructorThrowWhenInvalidPenalty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $classifier = new LogisticRegression(
            500,
            true,
            LogisticRegression::CONJUGATE_GRAD_TRAINING,
            'log',
            'invalid'
        );
    }

    public function testPredictSingleSample(): void
    {
        // AND problem
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1], [0.4, 0.4], [0.6, 0.6]];
        $targets = [0, 0, 0, 1, 0, 1];
        $classifier = new LogisticRegression();
        $classifier->train($samples, $targets);
        $this->assertEquals(0, $classifier->predict([0.1, 0.1]));
        $this->assertEquals(1, $classifier->predict([0.9, 0.9]));
    }

    public function testPredictSingleSampleWithBatchTraining(): void
    {
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1], [0.4, 0.4], [0.6, 0.6]];
        $targets = [0, 0, 0, 1, 0, 1];

        // $maxIterations is set to 10000 as batch training needs more
        // iteration to converge than CG method in general.
        $classifier = new LogisticRegression(
            10000,
            true,
            LogisticRegression::BATCH_TRAINING,
            'log',
            'L2'
        );
        $classifier->train($samples, $targets);
        $this->assertEquals(0, $classifier->predict([0.1, 0.1]));
        $this->assertEquals(1, $classifier->predict([0.9, 0.9]));
    }

    public function testPredictSingleSampleWithOnlineTraining(): void
    {
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1], [0.4, 0.4], [0.6, 0.6]];
        $targets = [0, 0, 0, 1, 0, 1];

        // $penalty is set to empty (no penalty) because L2 penalty seems to
        // prevent convergence in online training for this dataset.
        $classifier = new LogisticRegression(
            10000,
            true,
            LogisticRegression::ONLINE_TRAINING,
            'log',
            ''
        );
        $classifier->train($samples, $targets);
        $this->assertEquals(0, $classifier->predict([0.1, 0.1]));
        $this->assertEquals(1, $classifier->predict([0.9, 0.9]));
    }

    public function testPredictSingleSampleWithSSECost(): void
    {
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1], [0.4, 0.4], [0.6, 0.6]];
        $targets = [0, 0, 0, 1, 0, 1];
        $classifier = new LogisticRegression(
            500,
            true,
            LogisticRegression::CONJUGATE_GRAD_TRAINING,
            'sse',
            'L2'
        );
        $classifier->train($samples, $targets);
        $this->assertEquals(0, $classifier->predict([0.1, 0.1]));
        $this->assertEquals(1, $classifier->predict([0.9, 0.9]));
    }

    public function testPredictSingleSampleWithoutPenalty(): void
    {
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1], [0.4, 0.4], [0.6, 0.6]];
        $targets = [0, 0, 0, 1, 0, 1];
        $classifier = new LogisticRegression(
            500,
            true,
            LogisticRegression::CONJUGATE_GRAD_TRAINING,
            'log',
            ''
        );
        $classifier->train($samples, $targets);
        $this->assertEquals(0, $classifier->predict([0.1, 0.1]));
        $this->assertEquals(1, $classifier->predict([0.9, 0.9]));
    }

    public function testPredictMultiClassSample(): void
    {
        // By use of One-v-Rest, Perceptron can perform multi-class classification
        // The samples should be separable by lines perpendicular to the dimensions
        $samples = [
            [0, 0], [0, 1], [1, 0], [1, 1], // First group : a cluster at bottom-left corner in 2D
            [5, 5], [6, 5], [5, 6], [7, 5], // Second group: another cluster at the middle-right
            [3, 10], [3, 10], [3, 8], [3, 9],  // Third group : cluster at the top-middle
        ];
        $targets = [0, 0, 0, 0, 1, 1, 1, 1, 2, 2, 2, 2];

        $classifier = new LogisticRegression();
        $classifier->train($samples, $targets);
        $this->assertEquals(0, $classifier->predict([0.5, 0.5]));
        $this->assertEquals(1, $classifier->predict([6.0, 5.0]));
        $this->assertEquals(2, $classifier->predict([3.0, 9.5]));
    }

    public function testPredictProbabilitySingleSample(): void
    {
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1], [0.4, 0.4], [0.6, 0.6]];
        $targets = [0, 0, 0, 1, 0, 1];
        $classifier = new LogisticRegression();
        $classifier->train($samples, $targets);

        $property = new ReflectionProperty($classifier, 'classifiers');
        $property->setAccessible(true);
        $predictor = $property->getValue($classifier)[0];
        $method = new ReflectionMethod($predictor, 'predictProbability');
        $method->setAccessible(true);

        $zero = $method->invoke($predictor, [0.1, 0.1], 0);
        $one = $method->invoke($predictor, [0.1, 0.1], 1);
        $this->assertEquals(1, $zero + $one, '', 1e-6);
        $this->assertTrue($zero > $one);

        $zero = $method->invoke($predictor, [0.9, 0.9], 0);
        $one = $method->invoke($predictor, [0.9, 0.9], 1);
        $this->assertEquals(1, $zero + $one, '', 1e-6);
        $this->assertTrue($zero < $one);
    }

    public function testPredictProbabilityMultiClassSample(): void
    {
        $samples = [
            [0, 0], [0, 1], [1, 0], [1, 1],
            [5, 5], [6, 5], [5, 6], [6, 6],
            [3, 10], [3, 10], [3, 8], [3, 9],
        ];
        $targets = [0, 0, 0, 0, 1, 1, 1, 1, 2, 2, 2, 2];

        $classifier = new LogisticRegression();
        $classifier->train($samples, $targets);

        $property = new ReflectionProperty($classifier, 'classifiers');
        $property->setAccessible(true);

        $predictor = $property->getValue($classifier)[0];
        $method = new ReflectionMethod($predictor, 'predictProbability');
        $method->setAccessible(true);
        $zero = $method->invoke($predictor, [3.0, 9.5], 0);
        $not_zero = $method->invoke($predictor, [3.0, 9.5], 'not_0');

        $predictor = $property->getValue($classifier)[1];
        $method = new ReflectionMethod($predictor, 'predictProbability');
        $method->setAccessible(true);
        $one = $method->invoke($predictor, [3.0, 9.5], 1);
        $not_one = $method->invoke($predictor, [3.0, 9.5], 'not_1');

        $predictor = $property->getValue($classifier)[2];
        $method = new ReflectionMethod($predictor, 'predictProbability');
        $method->setAccessible(true);
        $two = $method->invoke($predictor, [3.0, 9.5], 2);
        $not_two = $method->invoke($predictor, [3.0, 9.5], 'not_2');

        $this->assertEquals(1, $zero + $not_zero, '', 1e-6);
        $this->assertEquals(1, $one + $not_one, '', 1e-6);
        $this->assertEquals(1, $two + $not_two, '', 1e-6);
        $this->assertTrue($zero < $two);
        $this->assertTrue($one < $two);
    }
}
