<?php

namespace PhpAb\Storage;

use PHPUnit_Framework_TestCase;

class SessionTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        // We need to abort sessions, else we cannot execute our test safely.
        @session_abort();

        // Let's make sure the session is empty before we start a test.
        $_SESSION = [];
    }

    private function startSession()
    {
        // Unfortunately we cannot start sessions in a normal way because phpunit already sends output to
        // the screen. Therefor we are suppressing the warning.
        @session_start();
    }

    /**
     * @covers PhpAb\Storage\Session::__construct
     */
    public function testConstructorWithValidNamespace()
    {
        // Arrange
        $this->startSession();

        // Act
        $session = new Session('namespace');

        // Assert
        // ...
    }

    /**
     * @covers PhpAb\Storage\Session::__construct
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The namespace is invalid.
     */
    public function testConstructorWithNullNamespace()
    {
        // Arrange
        // ...

        // Act
        $session = new Session(null);

        // Assert
        // ...
    }

    /**
     * @covers PhpAb\Storage\Session::__construct
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The namespace is invalid.
     */
    public function testConstructorWithNonStringNamespace()
    {
        // Arrange
        // ...

        // Act
        $session = new Session(0.3);

        // Assert
        // ...
    }

    /**
     * @covers PhpAb\Storage\Session::__construct
     * @expectedException RuntimeException
     * @expectedExceptionMessage The session has not been started.
     */
    public function testConstructorWithoutSessionStarted()
    {
        // Arrange
        // ...

        // Act
        $session = new Session('identifier');

        // Assert
        // ...
    }

    /**
     * @covers PhpAb\Storage\Session::has
     * @covers PhpAb\Storage\Session::getNamespace
     */
    public function testHasWithValidEntry()
    {
        // Arrange
        $this->startSession();

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
        $this->startSession();

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
        $this->startSession();

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
        $this->startSession();

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
        $this->startSession();

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
        $this->startSession();

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
        $this->startSession();

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
        $this->startSession();

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
        $this->startSession();

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
        $this->startSession();

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
        $this->startSession();

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
