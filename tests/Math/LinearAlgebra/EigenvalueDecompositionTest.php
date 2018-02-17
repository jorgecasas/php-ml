<?php

declare(strict_types=1);

namespace Phpml\Tests\Math\LinearAlgebra;

use Phpml\Math\LinearAlgebra\EigenvalueDecomposition;
use Phpml\Math\Matrix;
use PHPUnit\Framework\TestCase;

class EigenvalueDecompositionTest extends TestCase
{
    public function testKnownSymmetricMatrixDecomposition(): void
    {
        // First a simple example whose result is known and given in
        // http://www.cs.otago.ac.nz/cosc453/student_tutorials/principal_components.pdf
        $matrix = [
            [0.616555556, 0.615444444],
            [0.614444444, 0.716555556],
        ];

        $decomp = new EigenvalueDecomposition($matrix);

        self::assertEquals([0.0490833989, 1.28402771], $decomp->getRealEigenvalues(), '', 0.001);
        self::assertEquals([
            [-0.735178656, 0.677873399],
            [-0.677873399, -0.735178656],
        ], $decomp->getEigenvectors(), '', 0.001);
    }

    public function testMatrixWithAllZeroRow(): void
    {
        // http://www.wolframalpha.com/widgets/view.jsp?id=9aa01caf50c9307e9dabe159c9068c41
        $matrix = [
            [10, 0, 0],
            [0, 6, 0],
            [0, 0, 0],
        ];

        $decomp = new EigenvalueDecomposition($matrix);

        self::assertEquals([0.0, 6.0, 10.0], $decomp->getRealEigenvalues(), '', 0.0001);
        self::assertEquals([
            [0, 0, 1],
            [0, 1, 0],
            [1, 0, 0],
        ], $decomp->getEigenvectors(), '', 0.0001);
    }

    public function testMatrixThatCauseErrorWithStrictComparision(): void
    {
        // http://www.wolframalpha.com/widgets/view.jsp?id=9aa01caf50c9307e9dabe159c9068c41
        $matrix = [
            [1, 0, 3],
            [0, 1, 7],
            [3, 7, 4],
        ];

        $decomp = new EigenvalueDecomposition($matrix);

        self::assertEquals([-5.2620873481, 1.0, 10.2620873481], $decomp->getRealEigenvalues(), '', 0.000001);
        self::assertEquals([
            [-0.3042688, -0.709960552, 0.63511928],
            [-0.9191450, 0.393919298, 0.0],
            [0.25018574, 0.5837667, 0.7724140],
        ], $decomp->getEigenvectors(), '', 0.0001);
    }

    public function testRandomSymmetricMatrixEigenPairs(): void
    {
        // Acceptable error
        $epsilon = 0.001;
        // Secondly, generate a symmetric square matrix
        // and test for A.v=λ.v
        // (We, for now, omit non-symmetric matrices whose eigenvalues can be complex numbers)
        $len = 3;
        srand((int) microtime(true) * 1000);
        $A = array_fill(0, $len, array_fill(0, $len, 0.0));
        for ($i = 0; $i < $len; ++$i) {
            for ($k = 0; $k < $len; ++$k) {
                if ($i > $k) {
                    $A[$i][$k] = $A[$k][$i];
                } else {
                    $A[$i][$k] = random_int(0, 10);
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

            self::assertEquals($leftSide, $rightSide, '', $epsilon);
        }
    }
}
