<?php

declare(strict_types=1);

namespace tests\Phpml\Math\LinearAlgebra;

use Phpml\Math\LinearAlgebra\EigenvalueDecomposition;
use Phpml\Math\Matrix;
use PHPUnit\Framework\TestCase;

class EigenDecompositionTest extends TestCase
{
    public function testSymmetricMatrixEigenPairs(): void
    {
        // Acceptable error
        $epsilon = 0.001;

        // First a simple example whose result is known and given in
        // http://www.cs.otago.ac.nz/cosc453/student_tutorials/principal_components.pdf
        $matrix = [
            [0.616555556, 0.615444444],
            [0.614444444, 0.716555556]
        ];
        $knownEigvalues = [0.0490833989, 1.28402771];
        $knownEigvectors = [[-0.735178656, 0.677873399], [-0.677873399, -0.735178656]];

        $decomp = new EigenvalueDecomposition($matrix);
        $eigVectors = $decomp->getEigenvectors();
        $eigValues = $decomp->getRealEigenvalues();
        $this->assertEquals($knownEigvalues, $eigValues, '', $epsilon);
        $this->assertEquals($knownEigvectors, $eigVectors, '', $epsilon);

        // Secondly, generate a symmetric square matrix
        // and test for A.v=λ.v
        //
        // (We, for now, omit non-symmetric matrices whose eigenvalues can be complex numbers)
        $len = 3;
        $A = array_fill(0, $len, array_fill(0, $len, 0.0));
        $seed = microtime(true) * 1000;
        srand((int) $seed);
        for ($i = 0; $i < $len; ++$i) {
            for ($k = 0; $k < $len; ++$k) {
                if ($i > $k) {
                    $A[$i][$k] = $A[$k][$i];
                } else {
                    $A[$i][$k] = rand(0, 10);
                }
            }
        }

        $decomp = new EigenvalueDecomposition($A);
        $eigValues = $decomp->getRealEigenvalues();
        $eigVectors = $decomp->getEigenvectors();

        foreach ($eigValues as $index => $lambda) {
            $m1 = new Matrix($A);
            $m2 = (new Matrix($eigVectors[$index]))->transpose();

            // A.v=λ.v
            $leftSide = $m1->multiply($m2)->toArray();
            $rightSide = $m2->multiplyByScalar($lambda)->toArray();

            $this->assertEquals($leftSide, $rightSide, '', $epsilon);
        }
    }
}
