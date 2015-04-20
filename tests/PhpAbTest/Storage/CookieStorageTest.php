<?php

namespace PhpAbTest\Storage;

use PhpAb\AbTest;
use PhpAb\Storage\CookieStorage;
use PhpAbTestAsset\CallbackHandler;
use PhpAbTestAsset\EmptyStrategy;
use PHPUnit_Framework_TestCase;

class CookieStorageTest extends PHPUnit_Framework_TestCase
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

    /**
     * @expectedException RuntimeException
     */
    public function testClear()
    {
        // Arrange
        $storage = new CookieStorage('name', 10);

        // Act
        $storage->clear($this->abTest);

        // Assert
    }

    public function testReadEmptyCookie()
    {
        // Arrange
        $storage = new CookieStorage('name', 10);

        // Act
        $value = $storage->read($this->abTest);

        // Assert
        $this->assertNull($value);
    }

    public function testReadValidCookie()
    {
        // Arrange
        $_COOKIE['name_test'] = 'A';
        $storage = new CookieStorage('name', 10);

        // Act
        $value = $storage->read($this->abTest);

        // Assert
        $this->assertEquals('A', $value);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testWriteCookie()
    {
        // Arrange
        $storage = new CookieStorage('name', 10);

        // Act
        $storage->write($this->abTest, 'A');

        // Assert
        // ...
    }
}
