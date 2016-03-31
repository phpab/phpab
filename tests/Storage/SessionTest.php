<?php

namespace PhpAb\Storage;

use PHPUnit_Framework_TestCase;

class SessionTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        // Let's make sure the session is empty before we start a test.
        $_SESSION = [];
    }

    /**
     * @covers PhpAb\Storage\Session::__construct
     * @covers PhpAb\Storage\Session::getNamespace
     */
    public function testConstructorWithValidNamespace()
    {
        // Arrange
        $session = new Session('namespace');

        // Act
        $result = $session->getNamespace();

        // Assert
        $this->assertEquals('namespace', $result);
    }

    /**
     * @covers PhpAb\Storage\Session::__construct
     * @covers PhpAb\Storage\Session::getNamespace
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The namespace is invalid.
     */
    public function testConstructorWithInvalidNamespace()
    {
        // Arrange
        // ...

        // Act
        $session = new Session(null);

        // Assert
        // ...
    }

    /**
     * @covers PhpAb\Storage\Session::has
     */
    public function testHasWithValidEntry()
    {
        // Arrange
        $session = new Session('namespace');
        $session->set('identifier', 'participation');

        // Act
        $result = $session->has('identifier');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers PhpAb\Storage\Session::has
     */
    public function testHasWithInvalidEntry()
    {
        // Arrange
        $session = new Session('namespace');

        // Act
        $result = $session->has('identifier');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers PhpAb\Storage\Session::get
     */
    public function testGetValidEntry()
    {
        // Arrange
        $session = new Session('namespace');
        $session->set('identifier', 'participation');

        // Act
        $result = $session->get('identifier');

        // Assert
        $this->assertEquals('participation', $result);
    }

    /**
     * @covers PhpAb\Storage\Session::get
     */
    public function testGetInvalidEntry()
    {
        // Arrange
        $session = new Session('namespace');

        // Act
        $result = $session->get('identifier');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers PhpAb\Storage\Session::set
     */
    public function testSet()
    {
        // Arrange
        $session = new Session('namespace');

        // Act
        $session->set('identifier', 'participation');

        // Assert
        $this->assertEquals('participation', $session->get('identifier'));
    }

    /**
     * @covers PhpAb\Storage\Session::all
     */
    public function testAllWithEmptyStorage()
    {
        // Arrange
        $session = new Session('namespace');

        // Act
        $result = $session->all();

        // Assert
        $this->assertEquals([], $result);
    }

    /**
     * @covers PhpAb\Storage\Session::all
     */
    public function testAllWithFilledStorage()
    {
        // Arrange
        $session = new Session('namespace');
        $session->set('identifier1', 'participation1');
        $session->set('identifier2', 'participation2');

        // Act
        $result = $session->all();

        // Assert
        $this->assertEquals([
            'identifier1' => 'participation1',
            'identifier2' => 'participation2',
        ], $result);
    }

    /**
     * @covers PhpAb\Storage\Session::remove
     */
    public function testRemoveWithEmptyStorage()
    {
        // Arrange
        $session = new Session('namespace');

        // Act
        $result = $session->remove('identifier');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers PhpAb\Storage\Session::remove
     */
    public function testRemoveWithFilledStorage()
    {
        // Arrange
        $session = new Session('namespace');
        $session->set('identifier', 'participation');

        // Act
        $result = $session->remove('identifier');

        // Assert
        $this->assertEquals('participation', $result);
        $this->assertCount(0, $session->all());
    }

    /**
     * @covers PhpAb\Storage\Session::clear
     */
    public function testClearEmptyStorage()
    {
        // Arrange
        $session = new Session('namespace');

        // Act
        $result = $session->clear();

        // Assert
        $this->assertEquals([], $result);
    }

    /**
     * @covers PhpAb\Storage\Session::clear
     */
    public function testClearFilledStorage()
    {
        // Arrange
        $session = new Session('namespace');
        $session->set('identifier1', 'participation1');
        $session->set('identifier2', 'participation2');

        // Act
        $result = $session->clear();

        // Assert
        $this->assertEquals([
            'identifier1' => 'participation1',
            'identifier2' => 'participation2',
        ], $result);
        $this->assertCount(0, $session->all());
    }
}
