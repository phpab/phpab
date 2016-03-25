<?php

namespace PhpAbTest;

use PhpAb\AbTest;
use PhpAbTestAsset\CallbackHandler;
use PhpAbTestAsset\EmptyStrategy;
use PHPUnit_Framework_TestCase;

class AbTestTest extends PHPUnit_Framework_TestCase
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
        $abTest = new AbTest('test', null, $this->callbackB, $this->strategy);

        // Assert
        // ...
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCallbackB()
    {
        // Arrange

        // Act
        $abTest = new AbTest('test', $this->callbackA, null, $this->strategy);

        // Assert
        // ...
    }

    public function testGetName()
    {
        // Arrange

        // Act
        $abTest = new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy);

        // Assert
        $this->assertEquals('test', $abTest->getName());
    }

    public function testGetCallbackA()
    {
        // Arrange
        // ...
        //
        // Act
        $abTest = new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy);

        // Assert
        $this->assertEquals($this->callbackA, $abTest->getCallbackA());
        $this->assertEquals($this->callbackA, $abTest->getCallback('A'));
    }

    public function testGetCallbackB()
    {
        // Arrange
        // ...
        //
        // Act
        $abTest = new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy);

        // Assert
        $this->assertEquals($this->callbackB, $abTest->getCallbackB());
        $this->assertEquals($this->callbackB, $abTest->getCallback('B'));
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
        $abTest = new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy);

        // Execute
        $abTest->getCallback('invalid');
    }

    public function testGetParticipationStrategy()
    {
        // Arrange
        // ...
        //
        // Act
        $abTest = new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy);

        // Assert
        $this->assertEquals($this->strategy, $abTest->getParticipationStrategy());
    }
}
