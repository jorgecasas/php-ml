# MnistDataset

Helper class that loads data from MNIST dataset: [http://yann.lecun.com/exdb/mnist/](http://yann.lecun.com/exdb/mnist/)

> The MNIST database of handwritten digits, available from this page, has a training set of 60,000 examples, and a test set of 10,000 examples. It is a subset of a larger set available from NIST. The digits have been size-normalized and centered in a fixed-size image.
  It is a good database for people who want to try learning techniques and pattern recognition methods on real-world data while spending minimal efforts on preprocessing and formatting.

### Constructors Parameters

* $imagePath - (string) path to image file
* $labelPath - (string) path to label file

```
use Phpml\Dataset\MnistDataset;

$trainDataset = new MnistDataset('train-images-idx3-ubyte', 'train-labels-idx1-ubyte');
```

### Samples and labels

To get samples or labels, you can use getters:

```
$dataset->getSamples();
$dataset->getTargets();
```
