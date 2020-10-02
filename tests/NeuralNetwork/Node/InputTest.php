<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\Node;

use Phpml\NeuralNetwork\Node\Input;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    public function testInputInitialization(): void
    {
        $input = new Input();
        self::assertEquals(0.0, $input->getOutput());

        $input = new Input($value = 9.6);
        self::assertEquals($value, $input->getOutput());
    }

    public function testSetInput(): void
    {
        $input = new Input();
        $input->setInput($value = 6.9);

        self::assertEquals($value, $input->getOutput());
    }
}
