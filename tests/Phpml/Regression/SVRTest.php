<?php

declare (strict_types = 1);

namespace tests\Regression;

use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;

class SVRTest extends \PHPUnit_Framework_TestCase
{
    public function testPredictSingleFeatureSamples()
    {
        $delta = 0.01;

        $samples = [[60], [61], [62], [63], [65]];
        $targets = [3.1, 3.6, 3.8, 4, 4.1];

        $regression = new SVR(Kernel::LINEAR);
        $regression->train($samples, $targets);

        $this->assertEquals(4.03, $regression->predict([64]), '', $delta);

        $samples = [[9300], [10565], [15000], [15000], [17764], [57000], [65940], [73676], [77006], [93739], [146088], [153260]];
        $targets = [7100, 15500, 4400, 4400, 5900, 4600, 8800, 2000, 2750, 2550,  960, 1025];

        $regression = new SVR(Kernel::LINEAR);
        $regression->train($samples, $targets);

        $this->assertEquals(6236.12, $regression->predict([9300]), '', $delta);
        $this->assertEquals(4718.29, $regression->predict([57000]), '', $delta);
        $this->assertEquals(4081.69, $regression->predict([77006]), '', $delta);
        $this->assertEquals(6236.12, $regression->predict([9300]), '', $delta);
        $this->assertEquals(1655.26, $regression->predict([153260]), '', $delta);
    }

    public function testPredictMultiFeaturesSamples()
    {
        $delta = 0.01;

        $samples = [[73676, 1996], [77006, 1998], [10565, 2000], [146088, 1995], [15000, 2001], [65940, 2000], [9300, 2000], [93739, 1996], [153260, 1994], [17764, 2002], [57000, 1998], [15000, 2000]];
        $targets = [2000, 2750, 15500, 960, 4400, 8800, 7100, 2550, 1025, 5900, 4600, 4400];

        $regression = new SVR(Kernel::LINEAR);
        $regression->train($samples, $targets);

        $this->assertEquals(4109.82, $regression->predict([60000, 1996]), '', $delta);
        $this->assertEquals(4112.28, $regression->predict([60000, 2000]), '', $delta);
    }
}
