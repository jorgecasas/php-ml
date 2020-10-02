<?php

declare(strict_types=1);

namespace Phpml\Tests\Classification;

use Phpml\Classification\MLPClassifier;
use Phpml\Exception\InvalidArgumentException;
use Phpml\ModelManager;
use Phpml\NeuralNetwork\ActivationFunction;
use Phpml\NeuralNetwork\ActivationFunction\HyperbolicTangent;
use Phpml\NeuralNetwork\ActivationFunction\PReLU;
use Phpml\NeuralNetwork\ActivationFunction\Sigmoid;
use Phpml\NeuralNetwork\ActivationFunction\ThresholdedReLU;
use Phpml\NeuralNetwork\Node\Neuron;
use PHPUnit\Framework\TestCase;

class MLPClassifierTest extends TestCase
{
    public function testMLPClassifierLayersInitialization(): void
    {
        $mlp = new MLPClassifier(2, [2], [0, 1]);

        self::assertCount(3, $mlp->getLayers());

        $layers = $mlp->getLayers();

        // input layer
        self::assertCount(3, $layers[0]->getNodes());
        self::assertNotContainsOnly(Neuron::class, $layers[0]->getNodes());

        // hidden layer
        self::assertCount(3, $layers[1]->getNodes());
        self::assertNotContainsOnly(Neuron::class, $layers[1]->getNodes());

        // output layer
        self::assertCount(2, $layers[2]->getNodes());
        self::assertContainsOnly(Neuron::class, $layers[2]->getNodes());
    }

    public function testSynapsesGeneration(): void
    {
        $mlp = new MLPClassifier(2, [2], [0, 1]);
        $layers = $mlp->getLayers();

        foreach ($layers[1]->getNodes() as $node) {
            if ($node instanceof Neuron) {
                $synapses = $node->getSynapses();
                self::assertCount(3, $synapses);

                $synapsesNodes = $this->getSynapsesNodes($synapses);
                foreach ($layers[0]->getNodes() as $prevNode) {
                    self::assertContains($prevNode, $synapsesNodes);
                }
            }
        }
    }

    public function testBackpropagationLearning(): void
    {
        // Single layer 2 classes.
        $network = new MLPClassifier(2, [2], ['a', 'b'], 1000);
        $network->train(
            [[1, 0], [0, 1], [1, 1], [0, 0]],
            ['a', 'b', 'a', 'b']
        );

        self::assertEquals('a', $network->predict([1, 0]));
        self::assertEquals('b', $network->predict([0, 1]));
        self::assertEquals('a', $network->predict([1, 1]));
        self::assertEquals('b', $network->predict([0, 0]));
    }

    public function testBackpropagationTrainingReset(): void
    {
        // Single layer 2 classes.
        $network = new MLPClassifier(2, [2], ['a', 'b'], 1000);
        $network->train(
            [[1, 0], [0, 1]],
            ['a', 'b']
        );

        self::assertEquals('a', $network->predict([1, 0]));
        self::assertEquals('b', $network->predict([0, 1]));

        $network->train(
            [[1, 0], [0, 1]],
            ['b', 'a']
        );

        self::assertEquals('b', $network->predict([1, 0]));
        self::assertEquals('a', $network->predict([0, 1]));
    }

    public function testBackpropagationPartialTraining(): void
    {
        // Single layer 2 classes.
        $network = new MLPClassifier(2, [2], ['a', 'b'], 1000);
        $network->partialTrain(
            [[1, 0], [0, 1]],
            ['a', 'b']
        );

        self::assertEquals('a', $network->predict([1, 0]));
        self::assertEquals('b', $network->predict([0, 1]));

        $network->partialTrain(
            [[1, 1], [0, 0]],
            ['a', 'b']
        );

        self::assertEquals('a', $network->predict([1, 0]));
        self::assertEquals('b', $network->predict([0, 1]));
        self::assertEquals('a', $network->predict([1, 1]));
        self::assertEquals('b', $network->predict([0, 0]));
    }

    public function testBackpropagationLearningMultilayer(): void
    {
        // Multi-layer 2 classes.
        $network = new MLPClassifier(5, [3, 2], ['a', 'b', 'c'], 2000);
        $network->train(
            [[1, 0, 0, 0, 0], [0, 1, 1, 0, 0], [1, 1, 1, 1, 1], [0, 0, 0, 0, 0]],
            ['a', 'b', 'a', 'c']
        );

        self::assertEquals('a', $network->predict([1, 0, 0, 0, 0]));
        self::assertEquals('b', $network->predict([0, 1, 1, 0, 0]));
        self::assertEquals('a', $network->predict([1, 1, 1, 1, 1]));
        self::assertEquals('c', $network->predict([0, 0, 0, 0, 0]));
    }

    public function testBackpropagationLearningMulticlass(): void
    {
        // Multi-layer more than 2 classes.
        $network = new MLPClassifier(5, [3, 2], ['a', 'b', 4], 1000);
        $network->train(
            [[1, 0, 0, 0, 0], [0, 1, 0, 0, 0], [0, 0, 1, 1, 0], [1, 1, 1, 1, 1], [0, 0, 0, 0, 0]],
            ['a', 'b', 'a', 'a', 4]
        );

        self::assertEquals('a', $network->predict([1, 0, 0, 0, 0]));
        self::assertEquals('b', $network->predict([0, 1, 0, 0, 0]));
        self::assertEquals('a', $network->predict([0, 0, 1, 1, 0]));
        self::assertEquals('a', $network->predict([1, 1, 1, 1, 1]));
        self::assertEquals(4, $network->predict([0, 0, 0, 0, 0]));
    }

    /**
     * @dataProvider activationFunctionsProvider
     */
    public function testBackpropagationActivationFunctions(ActivationFunction $activationFunction): void
    {
        $network = new MLPClassifier(5, [3], ['a', 'b'], 1000, $activationFunction);
        $network->train(
            [[1, 0, 0, 0, 0], [0, 1, 0, 0, 0], [0, 0, 1, 1, 0], [1, 1, 1, 1, 1]],
            ['a', 'b', 'a', 'a']
        );

        self::assertEquals('a', $network->predict([1, 0, 0, 0, 0]));
        self::assertEquals('b', $network->predict([0, 1, 0, 0, 0]));
        self::assertEquals('a', $network->predict([0, 0, 1, 1, 0]));
        self::assertEquals('a', $network->predict([1, 1, 1, 1, 1]));
    }

    public function activationFunctionsProvider(): array
    {
        return [
            [new Sigmoid()],
            [new HyperbolicTangent()],
            [new PReLU()],
            [new ThresholdedReLU()],
        ];
    }

    public function testSaveAndRestore(): void
    {
        // Instantinate new Percetron trained for OR problem
        $samples = [[0, 0], [1, 0], [0, 1], [1, 1]];
        $targets = [0, 1, 1, 1];
        $classifier = new MLPClassifier(2, [2], [0, 1], 1000);
        $classifier->train($samples, $targets);
        $testSamples = [[0, 0], [1, 0], [0, 1], [1, 1]];
        $predicted = $classifier->predict($testSamples);

        $filename = 'perceptron-test-'.random_int(100, 999).'-'.uniqid('', false);
        $filepath = (string) tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        self::assertEquals($classifier, $restoredClassifier);
        self::assertEquals($predicted, $restoredClassifier->predict($testSamples));
    }

    public function testSaveAndRestoreWithPartialTraining(): void
    {
        $network = new MLPClassifier(2, [2], ['a', 'b'], 1000);
        $network->partialTrain(
            [[1, 0], [0, 1]],
            ['a', 'b']
        );

        self::assertEquals('a', $network->predict([1, 0]));
        self::assertEquals('b', $network->predict([0, 1]));

        $filename = 'perceptron-test-'.random_int(100, 999).'-'.uniqid('', false);
        $filepath = (string) tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($network, $filepath);

        /** @var MLPClassifier $restoredNetwork */
        $restoredNetwork = $modelManager->restoreFromFile($filepath);
        $restoredNetwork->partialTrain(
            [[1, 1], [0, 0]],
            ['a', 'b']
        );

        self::assertEquals('a', $restoredNetwork->predict([1, 0]));
        self::assertEquals('b', $restoredNetwork->predict([0, 1]));
        self::assertEquals('a', $restoredNetwork->predict([1, 1]));
        self::assertEquals('b', $restoredNetwork->predict([0, 0]));
    }

    public function testThrowExceptionOnInvalidLayersNumber(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MLPClassifier(2, [], [0, 1]);
    }

    public function testThrowExceptionOnInvalidPartialTrainingClasses(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $classifier = new MLPClassifier(2, [2], [0, 1]);
        $classifier->partialTrain(
            [[0, 1], [1, 0]],
            [0, 2],
            [0, 1, 2]
        );
    }

    public function testThrowExceptionOnInvalidClassesNumber(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MLPClassifier(2, [2], [0]);
    }

    public function testOutputWithLabels(): void
    {
        $output = (new MLPClassifier(2, [2, 2], ['T', 'F']))->getOutput();

        self::assertEquals(['T', 'F'], array_keys($output));
    }

    private function getSynapsesNodes(array $synapses): array
    {
        $nodes = [];
        foreach ($synapses as $synapse) {
            $nodes[] = $synapse->getNode();
        }

        return $nodes;
    }
}
