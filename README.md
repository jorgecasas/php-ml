# PHP-ML - Machine Learning library for PHP

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg)](https://php.net/)
[![Latest Stable Version](https://img.shields.io/packagist/v/php-ai/php-ml.svg)](https://packagist.org/packages/php-ai/php-ml)
[![Build Status](https://travis-ci.org/php-ai/php-ml.svg?branch=master)](https://travis-ci.org/php-ai/php-ml)
[![Documentation Status](https://readthedocs.org/projects/php-ml/badge/?version=master)](http://php-ml.readthedocs.org/)
[![Total Downloads](https://poser.pugx.org/php-ai/php-ml/downloads.svg)](https://packagist.org/packages/php-ai/php-ml)
[![License](https://poser.pugx.org/php-ai/php-ml/license.svg)](https://packagist.org/packages/php-ai/php-ml)
[![Coverage Status](https://coveralls.io/repos/github/php-ai/php-ml/badge.svg?branch=master)](https://coveralls.io/github/php-ai/php-ml?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/php-ai/php-ml/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/php-ai/php-ml/?branch=master)

<p align="center">
	<img src="https://github.com/php-ai/php-ml/raw/master/docs/assets/php-ml-logo.png" />
</p>

Fresh approach to Machine Learning in PHP. Algorithms, Cross Validation, Neural Network, Preprocessing, Feature Extraction and much more in one library.

PHP-ML requires PHP >= 7.1.

Simple example of classification:
```php
require_once __DIR__ . '/vendor/autoload.php';

use Phpml\Classification\KNearestNeighbors;

$samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
$labels = ['a', 'a', 'a', 'b', 'b', 'b'];

$classifier = new KNearestNeighbors();
$classifier->train($samples, $labels);

echo $classifier->predict([3, 2]);
// return 'b'
```

## Awards

<a href="http://www.yegor256.com/2016/10/23/award-2017.html">
  <img src="http://www.yegor256.com/images/award/2017/winner-itcraftsmanpl.png" width="400"/></a>

## Documentation

To find out how to use PHP-ML follow [Documentation](http://php-ml.readthedocs.org/).

## Installation

Currently this library is in the process of being developed, but You can install it with Composer:

```
composer require php-ai/php-ml
```

## Examples

Example scripts are available in a separate repository [php-ai/php-ml-examples](https://github.com/php-ai/php-ml-examples).

## Datasets

Public datasets are available in a separate repository [php-ai/php-ml-datasets](https://github.com/php-ai/php-ml-datasets).

## Features

* Association rule learning
    * [Apriori](http://php-ml.readthedocs.io/en/latest/machine-learning/association/apriori/)
* Classification
    * [SVC](http://php-ml.readthedocs.io/en/latest/machine-learning/classification/svc/)
    * [k-Nearest Neighbors](http://php-ml.readthedocs.io/en/latest/machine-learning/classification/k-nearest-neighbors/)
    * [Naive Bayes](http://php-ml.readthedocs.io/en/latest/machine-learning/classification/naive-bayes/)
    * Decision Tree (CART)
    * Ensemble Algorithms
        * Bagging (Bootstrap Aggregating)
        * Random Forest
        * AdaBoost
    * Linear
        * Adaline
        * Decision Stump
        * Perceptron
        * LogisticRegression
* Regression
    * [Least Squares](http://php-ml.readthedocs.io/en/latest/machine-learning/regression/least-squares/)
    * [SVR](http://php-ml.readthedocs.io/en/latest/machine-learning/regression/svr/)
* Clustering
    * [k-Means](http://php-ml.readthedocs.io/en/latest/machine-learning/clustering/k-means/)
    * [DBSCAN](http://php-ml.readthedocs.io/en/latest/machine-learning/clustering/dbscan/)
    * Fuzzy C-Means
* Metric
    * [Accuracy](http://php-ml.readthedocs.io/en/latest/machine-learning/metric/accuracy/)
    * [Confusion Matrix](http://php-ml.readthedocs.io/en/latest/machine-learning/metric/confusion-matrix/)
    * [Classification Report](http://php-ml.readthedocs.io/en/latest/machine-learning/metric/classification-report/)
* Workflow
    * [Pipeline](http://php-ml.readthedocs.io/en/latest/machine-learning/workflow/pipeline)
* Neural Network
    * [Multilayer Perceptron Classifier](http://php-ml.readthedocs.io/en/latest/machine-learning/neural-network/multilayer-perceptron-classifier/)
* Cross Validation
    * [Random Split](http://php-ml.readthedocs.io/en/latest/machine-learning/cross-validation/random-split/)
    * [Stratified Random Split](http://php-ml.readthedocs.io/en/latest/machine-learning/cross-validation/stratified-random-split/)
* Feature Selection
    * [Variance Threshold](http://php-ml.readthedocs.io/en/latest/machine-learning/feature-selection/variance-threshold/)
    * [SelectKBest](http://php-ml.readthedocs.io/en/latest/machine-learning/feature-selection/selectkbest/)
* Preprocessing
    * [Normalization](http://php-ml.readthedocs.io/en/latest/machine-learning/preprocessing/normalization/)
    * [Imputation missing values](http://php-ml.readthedocs.io/en/latest/machine-learning/preprocessing/imputation-missing-values/)
* Feature Extraction
    * [Token Count Vectorizer](http://php-ml.readthedocs.io/en/latest/machine-learning/feature-extraction/token-count-vectorizer/)
        * NGramTokenizer
        * WhitespaceTokenizer
        * WordTokenizer
    * [Tf-idf Transformer](http://php-ml.readthedocs.io/en/latest/machine-learning/feature-extraction/tf-idf-transformer/)
* Dimensionality Reduction
    * PCA (Principal Component Analysis)
    * Kernel PCA
    * LDA (Linear Discriminant Analysis)
* Datasets
    * [Array](http://php-ml.readthedocs.io/en/latest/machine-learning/datasets/array-dataset/)
    * [CSV](http://php-ml.readthedocs.io/en/latest/machine-learning/datasets/csv-dataset/)
    * [Files](http://php-ml.readthedocs.io/en/latest/machine-learning/datasets/files-dataset/)
    * [SVM](http://php-ml.readthedocs.io/en/latest/machine-learning/datasets/svm-dataset/)
    * [MNIST](http://php-ml.readthedocs.io/en/latest/machine-learning/datasets/mnist-dataset.md)
    * Ready to use:
        * [Iris](http://php-ml.readthedocs.io/en/latest/machine-learning/datasets/demo/iris/)
        * [Wine](http://php-ml.readthedocs.io/en/latest/machine-learning/datasets/demo/wine/)
        * [Glass](http://php-ml.readthedocs.io/en/latest/machine-learning/datasets/demo/glass/)
* Models management
    * [Persistency](http://php-ml.readthedocs.io/en/latest/machine-learning/model-manager/persistency/)
* Math
    * [Distance](http://php-ml.readthedocs.io/en/latest/math/distance/)
    * [Matrix](http://php-ml.readthedocs.io/en/latest/math/matrix/)
    * [Set](http://php-ml.readthedocs.io/en/latest/math/set/)
    * [Statistic](http://php-ml.readthedocs.io/en/latest/math/statistic/)
	* Linear Algebra

## Contribute

- [Guide: CONTRIBUTING.md](https://github.com/php-ai/php-ml/blob/master/CONTRIBUTING.md)
- [Issue Tracker: github.com/php-ai/php-ml](https://github.com/php-ai/php-ml/issues)
- [Source Code:  github.com/php-ai/php-ml](https://github.com/php-ai/php-ml)

You can find more about contributing in [CONTRIBUTING.md](CONTRIBUTING.md).

## License

PHP-ML is released under the MIT Licence. See the bundled LICENSE file for details.

## Author

Arkadiusz Kondas (@ArkadiuszKondas)
