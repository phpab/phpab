<?php

namespace PhpAbTest;

use PhpAb\RandomVariantChooser;
use PhpAb\Test;
use PhpAbTestAsset\CallbackHandler;
use PhpAbTestAsset\EmptyStrategy;
use PHPUnit_Framework_TestCase;

class TestTest extends PHPUnit_Framework_TestCase
{
    private $handler;
    private $strategy;
    private $callbackA;
    private $callbackB;

    public function setUp()
    {
        $this->handler = new CallbackHandler();
        $this->strategy = new EmptyStrategy();
        $this->callbackA = array($this->handler, 'methodA');
        $this->callbackB = array($this->handler, 'methodB');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCallbackA()
    {
        // Arrange

        // Act
        $abTest = new Test('test', null, ['B' =>  $this->callbackB], $this->strategy, new RandomVariantChooser());

        // Assert
        // ...
    }

    /**
     * @expectedException \PhpAb\Exception\ChoiceNotFoundException
     */
    public function testInvalidCallbackB()
    {
        // Arrange

        // Act
        $abTest = new Test('test', $this->callbackA, [], $this->strategy, new RandomVariantChooser());

        // Assert
        // ...
    }

    public function testGetName()
    {
        // Arrange

        // Act
        $abTest = new Test('test', $this->callbackA, ['B' => $this->callbackB], $this->strategy, new RandomVariantChooser());

        // Assert
        $this->assertEquals('test', $abTest->getName());
    }

    public function testGetCallbackA()
    {
        // Arrange
        // ...
        //
        // Act
        $abTest = new Test('test', $this->callbackA, ['B' => $this->callbackB], $this->strategy, new RandomVariantChooser());

        // Assert
        $this->assertEquals($this->callbackA, $abTest->getVariant('A'));
    }

    public function testGetCallbackB()
    {
        // Arrange
        // ...
        //
        // Act
        $abTest = new Test('test', $this->callbackA, ['B' => $this->callbackB], $this->strategy, new RandomVariantChooser());

        // Assert
        $this->assertEquals($this->callbackB, $abTest->getVariant('B'));
    }

    /**
     * @expectedException \PhpAb\Exception\ChoiceNotFoundException
     */
    public function testGetInvalidCallback()
    {
        // Arrange
        // ...
        //
        // Act
        $abTest = new Test('test', $this->callbackA, ['B' => $this->callbackB], $this->strategy, new RandomVariantChooser());

        // Execute
        $abTest->getVariant('invalid');
    }

    public function testGetParticipationStrategy()
    {
        // Arrange
        // ...
        //
        // Act
        $abTest = new Test('test', $this->callbackA, ['B' => $this->callbackB], $this->strategy, new RandomVariantChooser());

        // Assert
        $this->assertEquals($this->strategy, $abTest->getParticipationStrategy());
    }
}
