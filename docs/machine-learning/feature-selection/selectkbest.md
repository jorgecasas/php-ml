# SelectKBest

`SelectKBest` - select features according to the k highest scores.

## Constructor Parameters

* $k (int) - number of top features to select, rest will be removed (default: 10)
* $scoringFunction (ScoringFunction) - function that take samples and targets and return array with scores (default: ANOVAFValue)

```php
use Phpml\FeatureSelection\SelectKBest;

$transformer = new SelectKBest(2);
```

## Example of use

As an example we can perform feature selection on Iris dataset to retrieve only the two best features as follows:

```php
use Phpml\FeatureSelection\SelectKBest;
use Phpml\Dataset\Demo\IrisDataset;

$dataset = new IrisDataset();
$selector = new SelectKBest(2);
$selector->fit($samples = $dataset->getSamples(), $dataset->getTargets());
$selector->transform($samples);

/*
$samples[0] = [1.4, 0.2]; 
*/
```

## Scores

You can get a array with the calculated score for each feature. 
A higher value means that a given feature is better suited for learning.
Of course, the rating depends on the scoring function used.

```
use Phpml\FeatureSelection\SelectKBest;
use Phpml\Dataset\Demo\IrisDataset;

$dataset = new IrisDataset();
$selector = new SelectKBest(2);
$selector->fit($samples = $dataset->getSamples(), $dataset->getTargets());
$selector->scores();

/*
..array(4) {
  [0]=>
  float(119.26450218451)
  [1]=>
  float(47.364461402997)
  [2]=>
  float(1179.0343277002)
  [3]=>
  float(959.32440572573)
} 
*/
```

## Scoring function

Available scoring functions:

For classification:
 - **ANOVAFValue**
   The one-way ANOVA tests the null hypothesis that 2 or more groups have the same population mean.
   The test is applied to samples from two or more groups, possibly with differing sizes.

For regression:
 - **UnivariateLinearRegression**  
   Quick linear model for testing the effect of a single regressor, sequentially for many regressors.
   This is done in 2 steps:
     - 1. The cross correlation between each regressor and the target is computed, that is, ((X[:, i] - mean(X[:, i])) * (y - mean_y)) / (std(X[:, i]) *std(y)).
     - 2. It is converted to an F score 

## Pipeline

`SelectKBest` implements `Transformer` interface so it can be used as part of pipeline:

```php
use Phpml\FeatureSelection\SelectKBest;
use Phpml\Classification\SVC;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Pipeline;

$transformers = [
    new TfIdfTransformer(),
    new SelectKBest(3)
];
$estimator = new SVC();

$pipeline = new Pipeline($transformers, $estimator);
```
