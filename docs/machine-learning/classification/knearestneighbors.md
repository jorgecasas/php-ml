# KNearestNeighbors Classifier

Classifier implementing the k-nearest neighbors algorithm.

### Constructor Parameters

* $k - number of nearest neighbors to scan (default: 3)
* $distanceMetric - Distance class, default Euclidean (see Distance Metric  documentation)

```
$classifier = new KNearestNeighbors($k=4);
$classifier = new KNearestNeighbors($k=3, new Minkowski($lambda=4));
```

### Train

To train a classifier simply provide train samples and labels (as `array`):

```
$samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
$labels = ['a', 'a', 'a', 'b', 'b', 'b'];

$classifier = new KNearestNeighbors();
$classifier->train($samples, $labels);
```

### Predict

To predict sample class use `predict` method. You can provide one sample or array of samples:

```
$classifier->predict([3, 2]);
// return 'b'

$classifier->predict([[3, 2], [1, 5]]);
// return ['b', 'a']
```
