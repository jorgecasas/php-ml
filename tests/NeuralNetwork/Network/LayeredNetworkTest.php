<?php

declare(strict_types=1);

namespace Phpml\Tests\NeuralNetwork\Network;

use Phpml\NeuralNetwork\ActivationFunction;
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Network\LayeredNetwork;
use Phpml\NeuralNetwork\Node\Input;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class LayeredNetworkTest extends TestCase
{
    public function testLayersSettersAndGetters(): void
    {
        $network = $this->getLayeredNetworkMock();

        $network->addLayer($layer1 = new Layer());
        $network->addLayer($layer2 = new Layer());

        $this->assertEquals([$layer1, $layer2], $network->getLayers());
    }

    public function testGetLastLayerAsOutputLayer(): void
    {
        $network = $this->getLayeredNetworkMock();
        $network->addLayer($layer1 = new Layer());

        $this->assertEquals($layer1, $network->getOutputLayer());

        $network->addLayer($layer2 = new Layer());
        $this->assertEquals($layer2, $network->getOutputLayer());
    }

    public function testSetInputAndGetOutput(): void
    {
        $network = $this->getLayeredNetworkMock();
        $network->addLayer(new Layer(2, Input::class));

        $network->setInput($input = [34, 43]);
        $this->assertEquals($input, $network->getOutput());

        $network->addLayer(new Layer(1));
        $this->assertEquals([0.5], $network->getOutput());
    }

    public function testSetInputAndGetOutputWithCustomActivationFunctions(): void
    {
        $network = $this->getLayeredNetworkMock();
        $network->addLayer(new Layer(2, Input::class, $this->getActivationFunctionMock()));

        $network->setInput($input = [34, 43]);
        $this->assertEquals($input, $network->getOutput());
    }

    /**
     * @return LayeredNetwork|PHPUnit_Framework_MockObject_MockObject
     */
    private function getLayeredNetworkMock()
    {
        return $this->getMockForAbstractClass(LayeredNetwork::class);
    }

    /**
     * @return ActivationFunction|PHPUnit_Framework_MockObject_MockObject
     */
    private function getActivationFunctionMock()
    {
        return $this->getMockForAbstractClass(ActivationFunction::class);
    }
}
