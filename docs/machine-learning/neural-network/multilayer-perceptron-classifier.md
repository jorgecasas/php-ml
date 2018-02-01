# MLPClassifier

A multilayer perceptron (MLP) is a feedforward artificial neural network model that maps sets of input data onto a set of appropriate outputs.

## Constructor Parameters

* $inputLayerFeatures (int) - the number of input layer features
* $hiddenLayers (array) - array with the hidden layers configuration, each value represent number of neurons in each layers
* $classes (array) - array with the different training set classes (array keys are ignored)
* $iterations (int) - number of training iterations
* $learningRate (float) - the learning rate
* $activationFunction (ActivationFunction) - neuron activation function

```
use Phpml\Classification\MLPClassifier;
$mlp = new MLPClassifier(4, [2], ['a', 'b', 'c']);

// 4 nodes in input layer, 2 nodes in first hidden layer and 3 possible labels.

```

An Activation Function may also be passed in with each individual hidden layer. Example:

```
use Phpml\NeuralNetwork\ActivationFunction\PReLU;
use Phpml\NeuralNetwork\ActivationFunction\Sigmoid;
$mlp = new MLPClassifier(4, [[2, new PReLU], [2, new Sigmoid]], ['a', 'b', 'c']);
```

Instead of configuring each hidden layer as an array, they may also be configured with Layer objects. Example:

```
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Node\Neuron;
$layer1 = new Layer(2, Neuron::class, new PReLU);
$layer2 = new Layer(2, Neuron::class, new Sigmoid);
$mlp = new MLPClassifier(4, [$layer1, $layer2], ['a', 'b', 'c']);
```

## Train

To train a MLP simply provide train samples and labels (as array). Example:


```
$mlp->train(
    $samples = [[1, 0, 0, 0], [0, 1, 1, 0], [1, 1, 1, 1], [0, 0, 0, 0]],
    $targets = ['a', 'a', 'b', 'c']
);
```

Use partialTrain method to train in batches. Example:

```
$mlp->partialTrain(
    $samples = [[1, 0, 0, 0], [0, 1, 1, 0]],
    $targets = ['a', 'a']
);
$mlp->partialTrain(
    $samples = [[1, 1, 1, 1], [0, 0, 0, 0]],
    $targets = ['b', 'c']
);

```

You can update the learning rate between partialTrain runs:

```
$mlp->setLearningRate(0.1);
```

## Predict

To predict sample label use predict method. You can provide one sample or array of samples:

```
$mlp->predict([[1, 1, 1, 1], [0, 0, 0, 0]]);
// return ['b', 'c'];

```

## Activation Functions

* BinaryStep
* Gaussian
* HyperbolicTangent
* Parametric Rectified Linear Unit
* Sigmoid (default)
* Thresholded Rectified Linear Unit
