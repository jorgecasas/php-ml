# ArrayDataset

Helper class that holds data as PHP `array` type. Implements the `Dataset` interface which is used heavily in other classes.

### Constructors Parameters

* $samples - (array) of samples
* $labels - (array) of labels

```
use Phpml\Dataset\ArrayDataset;

$dataset = new ArrayDataset([[1, 1], [2, 1], [3, 2], [4, 1]], ['a', 'a', 'b', 'b']);
```

### Samples and labels

To get samples or labels you can use getters:

```
$dataset->getSamples();
$dataset->getTargets();
```

### Remove columns

You can remove columns by index numbers, for example:

```
use Phpml\Dataset\ArrayDataset;

$dataset = new ArrayDataset(
    [[1,2,3,4], [2,3,4,5], [3,4,5,6], [4,5,6,7]],
    ['a', 'a', 'b', 'b']
);

$dataset->removeColumns([0,2]);

// now from each sample column 0 and 2 are removed
// [[2,4], [3,5], [4,6], [5,7]]
```
