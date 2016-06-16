<?php

declare (strict_types = 1);

namespace tests;

use Phpml\Classification\SVC;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Pipeline;

class PipelineTest extends \PHPUnit_Framework_TestCase
{
    public function testPipelineConstruction()
    {
        $transformers = [
            new TfIdfTransformer(),
        ];
        $estimator = new SVC();

        $pipeline = new Pipeline($transformers, $estimator);

        $this->assertEquals($transformers, $pipeline->getTransformers());
        $this->assertEquals($estimator, $pipeline->getEstimator());
    }
}
