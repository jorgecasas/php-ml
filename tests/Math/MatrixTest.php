<?php

declare(strict_types=1);

namespace Phpml\Tests\Math;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Exception\MatrixException;
use Phpml\Math\Matrix;
use PHPUnit\Framework\TestCase;

class MatrixTest extends TestCase
{
    public function testThrowExceptionOnInvalidMatrixSupplied(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Matrix([[1, 2], [3]]);
    }

    public function testCreateMatrixFromFlatArray(): void
    {
        $flatArray = [1, 2, 3, 4];
        $matrix = Matrix::fromFlatArray($flatArray);

        self::assertEquals([[1], [2], [3], [4]], $matrix->toArray());
        self::assertEquals(4, $matrix->getRows());
        self::assertEquals(1, $matrix->getColumns());
        self::assertEquals($flatArray, $matrix->getColumnValues(0));
    }

    public function testThrowExceptionOnInvalidColumnNumber(): void
    {
        $this->expectException(MatrixException::class);
        $matrix = new Matrix([[1, 2, 3], [4, 5, 6]]);
        $matrix->getColumnValues(4);
    }

    public function testThrowExceptionOnGetDeterminantIfArrayIsNotSquare(): void
    {
        $this->expectException(MatrixException::class);
        $matrix = new Matrix([[1, 2, 3], [4, 5, 6]]);
        $matrix->getDeterminant();
    }

    public function testGetMatrixDeterminant(): void
    {
        //http://matrix.reshish.com/determinant.php
        $matrix = new Matrix([
            [3, 3, 3],
            [4, 2, 1],
            [5, 6, 7],
        ]);
        self::assertEquals(-3, $matrix->getDeterminant());

        $matrix = new Matrix([
            [1, 2, 3, 3, 2, 1],
            [1 / 2, 5, 6, 7, 1, 1],
            [3 / 2, 7 / 2, 2, 0, 6, 8],
            [1, 8, 10, 1, 2, 2],
            [1 / 4, 4, 1, 0, 2, 3 / 7],
            [1, 8, 7, 5, 4, 4 / 5],
        ]);
        self::assertEqualsWithDelta(1116.5035, $matrix->getDeterminant(), $delta = 0.0001);
    }

    public function testMatrixTranspose(): void
    {
        $matrix = new Matrix([
            [3, 3, 3],
            [4, 2, 1],
            [5, 6, 7],
        ]);

        $transposedMatrix = [
            [3, 4, 5],
            [3, 2, 6],
            [3, 1, 7],
        ];

        self::assertEquals($transposedMatrix, $matrix->transpose()->toArray());
    }

    public function testThrowExceptionOnMultiplyWhenInconsistentMatrixSupplied(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $matrix1 = new Matrix([[1, 2, 3], [4, 5, 6]]);
        $matrix2 = new Matrix([[3, 2, 1], [6, 5, 4]]);
        $matrix1->multiply($matrix2);
    }

    public function testMatrixMultiplyByMatrix(): void
    {
        $matrix1 = new Matrix([
            [1, 2, 3],
            [4, 5, 6],
        ]);

        $matrix2 = new Matrix([
            [7, 8],
            [9, 10],
            [11, 12],
        ]);

        $product = [
            [58, 64],
            [139, 154],
        ];

        self::assertEquals($product, $matrix1->multiply($matrix2)->toArray());
    }

    public function testDivideByScalar(): void
    {
        $matrix = new Matrix([
            [4, 6, 8],
            [2, 10, 20],
        ]);

        $quotient = [
            [2, 3, 4],
            [1, 5, 10],
        ];

        self::assertEquals($quotient, $matrix->divideByScalar(2)->toArray());
    }

    public function testThrowExceptionWhenInverseIfArrayIsNotSquare(): void
    {
        $this->expectException(MatrixException::class);
        $matrix = new Matrix([[1, 2, 3], [4, 5, 6]]);
        $matrix->inverse();
    }

    public function testThrowExceptionWhenInverseIfMatrixIsSingular(): void
    {
        $this->expectException(MatrixException::class);
        $matrix = new Matrix([
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ]);
        $matrix->inverse();
    }

    public function testInverseMatrix(): void
    {
        //http://ncalculators.com/matrix/inverse-matrix.htm
        $matrix = new Matrix([
            [3, 4, 2],
            [4, 5, 5],
            [1, 1, 1],
        ]);

        $inverseMatrix = [
            [0, -1, 5],
            [1 / 2, 1 / 2, -7 / 2],
            [-1 / 2, 1 / 2, -1 / 2],
        ];

        self::assertEqualsWithDelta($inverseMatrix, $matrix->inverse()->toArray(), $delta = 0.0001);
    }

    public function testCrossOutMatrix(): void
    {
        $matrix = new Matrix([
            [3, 4, 2],
            [4, 5, 5],
            [1, 1, 1],
        ]);

        $crossOuted = [
            [3, 2],
            [1, 1],
        ];

        self::assertEquals($crossOuted, $matrix->crossOut(1, 1)->toArray());
    }

    public function testToScalar(): void
    {
        $matrix = new Matrix([[1, 2, 3], [3, 2, 3]]);

        self::assertEquals($matrix->toScalar(), 1);
    }

    public function testMultiplyByScalar(): void
    {
        $matrix = new Matrix([
            [4, 6, 8],
            [2, 10, 20],
        ]);

        $result = [
            [-8, -12, -16],
            [-4, -20, -40],
        ];

        self::assertEquals($result, $matrix->multiplyByScalar(-2)->toArray());
    }

    public function testAdd(): void
    {
        $array1 = [1, 1, 1];
        $array2 = [2, 2, 2];
        $result = [3, 3, 3];

        $m1 = new Matrix($array1);
        $m2 = new Matrix($array2);

        self::assertEquals($result, $m1->add($m2)->toArray()[0]);
    }

    public function testSubtract(): void
    {
        $array1 = [1, 1, 1];
        $array2 = [2, 2, 2];
        $result = [-1, -1, -1];

        $m1 = new Matrix($array1);
        $m2 = new Matrix($array2);

        self::assertEquals($result, $m1->subtract($m2)->toArray()[0]);
    }

    public function testTransposeArray(): void
    {
        $array = [
            [1, 1, 1],
            [2, 2, 2],
        ];
        $transposed = [
            [1, 2],
            [1, 2],
            [1, 2],
        ];

        self::assertEquals($transposed, Matrix::transposeArray($array));
    }

    public function testDot(): void
    {
        $vect1 = [2, 2, 2];
        $vect2 = [3, 3, 3];
        $dot = [18];

        self::assertEquals($dot, Matrix::dot($vect1, $vect2));

        $matrix1 = [[1, 1], [2, 2]];
        $matrix2 = [[3, 3], [3, 3], [3, 3]];
        $dot = [6, 12];
        self::assertEquals($dot, Matrix::dot($matrix2, $matrix1));
    }

    /**
     * @dataProvider dataProviderForFrobeniusNorm
     */
    public function testFrobeniusNorm(array $matrix, float $norm): void
    {
        self::assertEqualsWithDelta($norm, (new Matrix($matrix))->frobeniusNorm(), 0.0001);
    }

    public function dataProviderForFrobeniusNorm(): array
    {
        return [
            [
                [
                    [1, -7],
                    [2, 3],
                ], 7.93725,
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 9.643651,
            ],
            [
                [
                    [1, 5, 3, 9],
                    [2, 3, 4, 12],
                    [4, 2, 5, 11],
                ], 21.330729,
            ],
            [
                [
                    [1, 5, 3],
                    [2, 3, 4],
                    [4, 2, 5],
                    [6, 6, 3],
                ], 13.784049,
            ],
            [
                [
                    [5, -4, 2],
                    [-1, 2, 3],
                    [-2, 1, 0],
                ], 8,
            ],
        ];
    }
}
