<?php

declare (strict_types = 1);

namespace tests\Regression;

use Phpml\Regression\LeastSquares;

class LeastSquaresTest extends \PHPUnit_Framework_TestCase
{
    public function testPredictSingleFeatureSamples()
    {
        $delta = 0.01;

        //https://www.easycalculation.com/analytical/learn-least-square-regression.php
        $samples = [[1, 60], [1, 61], [1, 62], [1, 63], [1, 65]];
        $targets = [[3.1], [3.6], [3.8], [4], [4.1]];

        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        $this->assertEquals(4.06, $regression->predict([64]), '', $delta);

        //http://www.stat.wmich.edu/s216/book/node127.html
        $samples = [[1, 9300], [1, 10565], [1, 15000], [1, 15000], [1, 17764], [1, 57000], [1, 65940], [1, 73676], [1, 77006], [1, 93739], [1, 146088], [1, 153260]];
        $targets = [[7100], [15500], [4400], [4400], [5900], [4600], [8800], [2000], [2750], [2550],  [960], [1025]];

        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        $this->assertEquals(7659.35, $regression->predict([9300]), '', $delta);
        $this->assertEquals(5213.81, $regression->predict([57000]), '', $delta);
        $this->assertEquals(4188.13, $regression->predict([77006]), '', $delta);
        $this->assertEquals(7659.35, $regression->predict([9300]), '', $delta);
        $this->assertEquals(278.66, $regression->predict([153260]), '', $delta);
    }

    public function testPredictMultiFeaturesSamples()
    {
        $delta = 0.01;

        //http://www.stat.wmich.edu/s216/book/node129.html
        $samples = [[1,  73676, 1996], [1,  77006, 1998], [1,  10565, 2000], [1, 146088, 1995], [1,  15000, 2001], [1,  65940, 2000], [1,   9300, 2000], [1,  93739, 1996], [1, 153260, 1994], [1,  17764, 2002], [1,  57000, 1998], [1,  15000, 2000]];
        $targets = [[2000], [2750], [15500], [960], [4400], [8800], [7100], [2550], [1025], [5900], [4600], [4400]];

        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        $this->assertEquals(-800614.957, $regression->getIntercept(), '', $delta);
        $this->assertEquals([-0.0327, 404.14], $regression->getCoefficients(), '', $delta);
        $this->assertEquals(4094.82, $regression->predict([60000, 1996]), '', $delta);
        $this->assertEquals(5711.40, $regression->predict([60000, 2000]), '', $delta);
    }
}
