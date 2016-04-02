<?php

namespace PhpAb\Participation;

use PhpAb\Storage\Runtime;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    private $storage;

    public function setUp()
    {
        $this->storage = new Runtime();
    }

    public function testCheckParticipation()
    {
        // Arrange
        $manager = new Manager($this->storage);

        // Act
        $result = $manager->participates('foo');

        // Assert
        $this->assertFalse($result);
    }

    // More to come

    public function tearDown()
    {
        $this->storage->clear();
    }
}
