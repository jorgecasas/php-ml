<?php

declare(strict_types=1);

namespace Phpml\Tests\Math\LinearAlgebra;

use Phpml\Exception\MatrixException;
use Phpml\Math\LinearAlgebra\LUDecomposition;
use Phpml\Math\Matrix;
use PHPUnit\Framework\TestCase;

/**
 * LUDecomposition is used and tested in Matrix::inverse method so not all tests are required
 */
final class LUDecompositionTest extends TestCase
{
    public function testNotSquareMatrix(): void
    {
        $this->expectException(MatrixException::class);

        new LUDecomposition(new Matrix([1, 2, 3, 4, 5]));
    }

    public function testSolveWithInvalidMatrix(): void
    {
        $this->expectException(MatrixException::class);

        $lu = new LUDecomposition(new Matrix([[1, 2], [3, 4]]));
        $lu->solve(new Matrix([1, 2, 3]));
    }
}
