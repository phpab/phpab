<?php

namespace PhpAbTest\Storage;

use PhpAb\AbTest;
use PhpAb\Storage\SessionStorage;
use PhpAbTestAsset\CallbackHandler;
use PhpAbTestAsset\EmptyStrategy;
use PHPUnit_Framework_TestCase;

class SessionStorageTest extends PHPUnit_Framework_TestCase
{
    private $handler;
    private $strategy;
    private $callbackA;
    private $callbackB;
    private $abTest;

    public function setUp()
    {
        $this->handler = new CallbackHandler();
        $this->strategy = new EmptyStrategy();
        $this->callbackA = array($this->handler, 'methodA');
        $this->callbackB = array($this->handler, 'methodB');
        $this->abTest = new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy);
    }

    public function testClear()
    {
        // Arrange
        $_SESSION = array();
        $storage = new SessionStorage('name');

        // Act
        $storage->clear($this->abTest);

        // Assert
        $this->assertEquals(array(), $_SESSION);
    }

    public function testReadEmptySession()
    {
        // Arrange
        $_SESSION = array();
        $storage = new SessionStorage('name');

        // Act
        $value = $storage->read($this->abTest);

        // Assert
        $this->assertNull($value);
    }

    public function testReadValidSession()
    {
        // Arrange
        $_SESSION = array('name_test' => 'A');
        $storage = new SessionStorage('name', 10);

        // Act
        $value = $storage->read($this->abTest);

        // Assert
        $this->assertEquals('A', $value);
    }

    public function testWriteCookie()
    {
        // Arrange
        $storage = new SessionStorage('name');

        // Act
        $storage->write($this->abTest, 'A');

        // Assert
        // ...
    }
}
