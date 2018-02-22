CHANGELOG
=========

This changelog references the relevant changes done in PHP-ML library.

* 0.6.2 (2018-02-22)
    * Fix Apriori array keys (#238)

* 0.6.1 (2018-02-18)
    * Fix KMeans and EigenvalueDecomposition (#235)

* 0.6.0 (2018-02-16)
    * feature [FeatureSelection] implement SelectKBest with scoring functions (#232)
    * feature [FeatureSelection] implement VarianceThreshold - simple baseline approach to feature selection.  (#228)
    * feature [Classification] support probability estimation in SVC (#218)
    * feature [NeuralNetwork] configure an Activation Function per hidden layer (#208)
    * feature [NeuralNetwork] Ability to update learningRate in MLP (#160)
    * feature [Metric] Choose averaging method in classification report (#205)
    * enhancement Add phpstan strict rules (#233)
    * enhancement Flatten directory structure (#220)
    * enhancement Update phpunit/phpunit (#219)
    * enhancement Cache dependencies installed with composer on Travis (#215)
    * enhancement Add support for coveralls.io (#153)
    * enhancement Add phpstan and easy coding standards (#156, #168)
    * enhancement Throw exception when libsvm command fails to run (#200, #202)
    * enhancement Normalize composer.json and sort packages (#214, #210)
    * enhancement Rewrite DBSCAN (#185)
    * fix phpunit include tests path (#230)
    * fix support of a rule in Apriori (#229)
    * fix apriori generates an empty array as a part of the frequent item sets (#224)
    * fix backpropagation random error (#157)
    * fix logistic regression implementation (#169)
    * fix activation functions support (#163)
    * fix string representation of integer labels issue in NaiveBayes (#206)
    * fix the implementation of conjugate gradient method (#184)
    * typo, tests and documentation fixes (#234, #221, #181, #183, #155, #159, #165, #187, #154, #191, #203, #209, #213, #212, #211)

* 0.5.0 (2017-11-14)
    * general [php] Upgrade to PHP 7.1 (#150)
    * general [coding standard] fix imports order and drop unused docs typehints
    * feature [NeuralNetwork] Add PReLU activation function (#128)
    * feature [NeuralNetwork] Add ThresholdedReLU activation function (#129)
    * feature [Dataset] Support CSV with long lines (#119)
    * feature [NeuralNetwork] Neural networks partial training and persistency (#91)
    * feature Add french stopwords (#92)
    * feature New methods: setBinPath, setVarPath in SupportVectorMachine (#73)
    * feature Linear Discrimant Analysis (LDA) (#82)
    * feature Linear algebra operations, Dimensionality reduction and some other minor changes (#81)
    * feature Partial training base (#78)
    * feature Add delimiter option for CsvDataset (#66)
    * feature LogisticRegression classifier & Optimization methods (#63)
    * feature Additional training for SVR (#59)
    * optimization Comparison - replace eval (#130)
    * optimization Use C-style casts (#124)
    * optimization Speed up DataTransformer (#122)
    * bug DBSCAN fix for associative keys and array_merge performance optimization (#139)
    * bug Ensure user-provided SupportVectorMachine paths are valid (#126)
    * bug [DecisionTree] Fix string cast #120 (#121)
    * bug fix invalid typehint for subs method (#110)
    * bug Fix samples transformation in Pipeline training (#94)
    * bug Fix division by 0 error during normalization (#83)
    * bug Fix wrong docs references (#79)

* 0.4.0 (2017-02-23)
    * feature [Classification] - Ensemble Classifiers : Bagging and RandomForest by Mustafa Karabulut
    * feature [Classification] - RandomForest::getFeatureImportances() method by Mustafa Karabulut
    * feature [Classification] - Linear classifiers: Perceptron, Adaline, DecisionStump by Mustafa Karabulut
    * feature [Classification] - AdaBoost algorithm by Mustafa Karabulut
    * bug [Math] - Check if matrix is singular doing inverse by Povilas Susinskas
    * optimization - Euclidean optimization by Mustafa Karabulut

* 0.3.0 (2017-02-04)
    * feature [Persistency] - ModelManager - save and restore trained models by David Monllaó
    * feature [Classification] - DecisionTree implementation by Mustafa Karabulut
    * feature [Clustering] - Fuzzy C Means implementation by Mustafa Karabulut
    * other small fixes and code styles refactors

* 0.2.1 (2016-11-20)
    * feature [Association] - Apriori algorithm implementation
    * bug [Metric] - division by zero

* 0.2.0 (2016-08-14)
    * feature [NeuralNetwork] - MultilayerPerceptron and Backpropagation training 

* 0.1.2 (2016-07-24)
    * feature [Dataset] - FilesDataset - load dataset from files (folder names as targets)
    * feature [Metric] - ClassificationReport - report about trained classifier
    * bug [Feature Extraction] - fix problem with token count vectorizer array order
    * tests [General] - add more tests for specific conditions

* 0.1.1 (2016-07-12)
    * feature [Cross Validation] Stratified Random Split - equal distribution for targets in split
    * feature [General] Documentation - add missing pages (Pipeline, ConfusionMatrix and TfIdfTransformer) and fix links 

* 0.1.0 (2016-07-08)
    * first develop release
    * base tools for Machine Learning: Algorithms, Cross Validation, Preprocessing, Feature Extraction
    * bug [General] #7 - PHP-ML doesn't work on Mac
