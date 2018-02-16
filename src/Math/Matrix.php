<?php

declare(strict_types=1);

namespace Phpml\Math;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Exception\MatrixException;
use Phpml\Math\LinearAlgebra\LUDecomposition;

class Matrix
{
    /**
     * @var array
     */
    private $matrix = [];

    /**
     * @var int
     */
    private $rows;

    /**
     * @var int
     */
    private $columns;

    /**
     * @var float
     */
    private $determinant;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(array $matrix, bool $validate = true)
    {
        // When a row vector is given
        if (!is_array($matrix[0])) {
            $this->rows = 1;
            $this->columns = count($matrix);
            $matrix = [$matrix];
        } else {
            $this->rows = count($matrix);
            $this->columns = count($matrix[0]);
        }

        if ($validate) {
            for ($i = 0; $i < $this->rows; ++$i) {
                if (count($matrix[$i]) !== $this->columns) {
                    throw InvalidArgumentException::matrixDimensionsDidNotMatch();
                }
            }
        }

        $this->matrix = $matrix;
    }

    public static function fromFlatArray(array $array): self
    {
        $matrix = [];
        foreach ($array as $value) {
            $matrix[] = [$value];
        }

        return new self($matrix);
    }

    public function toArray(): array
    {
        return $this->matrix;
    }

    public function toScalar(): float
    {
        return $this->matrix[0][0];
    }

    public function getRows(): int
    {
        return $this->rows;
    }

    public function getColumns(): int
    {
        return $this->columns;
    }

    /**
     * @throws MatrixException
     */
    public function getColumnValues($column): array
    {
        if ($column >= $this->columns) {
            throw MatrixException::columnOutOfRange();
        }

        return array_column($this->matrix, $column);
    }

    /**
     * @return float|int
     *
     * @throws MatrixException
     */
    public function getDeterminant()
    {
        if ($this->determinant !== null) {
            return $this->determinant;
        }

        if (!$this->isSquare()) {
            throw MatrixException::notSquareMatrix();
        }

        $lu = new LUDecomposition($this);

        return $this->determinant = $lu->det();
    }

    public function isSquare(): bool
    {
        return $this->columns === $this->rows;
    }

    public function transpose(): self
    {
        if ($this->rows == 1) {
            $matrix = array_map(function ($el) {
                return [$el];
            }, $this->matrix[0]);
        } else {
            $matrix = array_map(null, ...$this->matrix);
        }

        return new self($matrix, false);
    }

    public function multiply(self $matrix): self
    {
        if ($this->columns != $matrix->getRows()) {
            throw InvalidArgumentException::inconsistentMatrixSupplied();
        }

        $product = [];
        $multiplier = $matrix->toArray();
        for ($i = 0; $i < $this->rows; ++$i) {
            $columns = $matrix->getColumns();
            for ($j = 0; $j < $columns; ++$j) {
                $product[$i][$j] = 0;
                for ($k = 0; $k < $this->columns; ++$k) {
                    $product[$i][$j] += $this->matrix[$i][$k] * $multiplier[$k][$j];
                }
            }
        }

        return new self($product, false);
    }

    public function divideByScalar($value): self
    {
        $newMatrix = [];
        for ($i = 0; $i < $this->rows; ++$i) {
            for ($j = 0; $j < $this->columns; ++$j) {
                $newMatrix[$i][$j] = $this->matrix[$i][$j] / $value;
            }
        }

        return new self($newMatrix, false);
    }

    public function multiplyByScalar($value): self
    {
        $newMatrix = [];
        for ($i = 0; $i < $this->rows; ++$i) {
            for ($j = 0; $j < $this->columns; ++$j) {
                $newMatrix[$i][$j] = $this->matrix[$i][$j] * $value;
            }
        }

        return new self($newMatrix, false);
    }

    /**
     * Element-wise addition of the matrix with another one
     */
    public function add(self $other): self
    {
        return $this->_add($other);
    }

    /**
     * Element-wise subtracting of another matrix from this one
     */
    public function subtract(self $other): self
    {
        return $this->_add($other, -1);
    }

    public function inverse(): self
    {
        if (!$this->isSquare()) {
            throw MatrixException::notSquareMatrix();
        }

        $LU = new LUDecomposition($this);
        $identity = $this->getIdentity();
        $inverse = $LU->solve($identity);

        return new self($inverse, false);
    }

    public function crossOut(int $row, int $column): self
    {
        $newMatrix = [];
        $r = 0;
        for ($i = 0; $i < $this->rows; ++$i) {
            $c = 0;
            if ($row != $i) {
                for ($j = 0; $j < $this->columns; ++$j) {
                    if ($column != $j) {
                        $newMatrix[$r][$c] = $this->matrix[$i][$j];
                        ++$c;
                    }
                }

                ++$r;
            }
        }

        return new self($newMatrix, false);
    }

    public function isSingular(): bool
    {
        return $this->getDeterminant() == 0;
    }

    /**
     * Frobenius norm (Hilbert–Schmidt norm, Euclidean norm) (‖A‖F)
     * Square root of the sum of the square of all elements.
     *
     * https://en.wikipedia.org/wiki/Matrix_norm#Frobenius_norm
     *
     *          _____________
     *         /ᵐ   ⁿ
     * ‖A‖F = √ Σ   Σ  |aᵢⱼ|²
     *         ᵢ₌₁ ᵢ₌₁
     */
    public function frobeniusNorm(): float
    {
        $squareSum = 0;
        for ($i = 0; $i < $this->rows; ++$i) {
            for ($j = 0; $j < $this->columns; ++$j) {
                $squareSum += ($this->matrix[$i][$j]) ** 2;
            }
        }

        return sqrt($squareSum);
    }

    /**
     * Returns the transpose of given array
     */
    public static function transposeArray(array $array): array
    {
        return (new self($array, false))->transpose()->toArray();
    }

    /**
     * Returns the dot product of two arrays<br>
     * Matrix::dot(x, y) ==> x.y'
     */
    public static function dot(array $array1, array $array2): array
    {
        $m1 = new self($array1, false);
        $m2 = new self($array2, false);

        return $m1->multiply($m2->transpose())->toArray()[0];
    }

    /**
     * Element-wise addition or substraction depending on the given sign parameter
     */
    private function _add(self $other, int $sign = 1): self
    {
        $a1 = $this->toArray();
        $a2 = $other->toArray();

        $newMatrix = [];
        for ($i = 0; $i < $this->rows; ++$i) {
            for ($k = 0; $k < $this->columns; ++$k) {
                $newMatrix[$i][$k] = $a1[$i][$k] + $sign * $a2[$i][$k];
            }
        }

        return new self($newMatrix, false);
    }

    /**
     * Returns diagonal identity matrix of the same size of this matrix
     */
    private function getIdentity(): self
    {
        $array = array_fill(0, $this->rows, array_fill(0, $this->columns, 0));
        for ($i = 0; $i < $this->rows; ++$i) {
            $array[$i][$i] = 1;
        }

        return new self($array, false);
    }
}
