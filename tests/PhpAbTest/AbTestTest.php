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
