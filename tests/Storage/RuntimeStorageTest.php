<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage;

use PHPUnit\Framework\TestCase;

class RuntimeStorageTest extends TestCase
{
    /**
     * @covers PhpAb\Storage\RuntimeStorage::__construct
     */
    public function testConstructor()
    {
        // Arrange
        $storage = new RuntimeStorage();

        // Act
        $result = $storage->all();

        // Assert
        $this->assertInternalType('array', $result);
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::has
     */
    public function testHasWithValidEntry()
    {
        // Arrange
        $storage = new RuntimeStorage();
        $storage->set('identifier', 'participation');

        // Act
        $result = $storage->has('identifier');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::has
     */
    public function testHasWithInvalidEntry()
    {
        // Arrange
        $storage = new RuntimeStorage();

        // Act
        $result = $storage->has('identifier');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::has
     */
    public function testHasWithZeroEntry()
    {
        // Arrange
        $storage = new RuntimeStorage();
        $storage->set('0', 'participation');

        // Act
        $result = $storage->has('0');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::get
     */
    public function testGetValidEntry()
    {
        // Arrange
        $storage = new RuntimeStorage();
        $storage->set('identifier', 'participation');

        // Act
        $result = $storage->get('identifier');

        // Assert
        $this->assertEquals('participation', $result);
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::get
     */
    public function testGetInvalidEntry()
    {
        // Arrange
        $storage = new RuntimeStorage();

        // Act
        $result = $storage->get('identifier');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::set
     */
    public function testSet()
    {
        // Arrange
        $storage = new RuntimeStorage();

        // Act
        $storage->set('identifier', 'participation');

        // Assert
        $this->assertEquals('participation', $storage->get('identifier'));
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::all
     */
    public function testAllWithEmptyStorage()
    {
        // Arrange
        $storage = new RuntimeStorage();

        // Act
        $result = $storage->all();

        // Assert
        $this->assertEquals([], $result);
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::all
     */
    public function testAllWithFilledStorage()
    {
        // Arrange
        $storage = new RuntimeStorage();
        $storage->set('identifier1', 'participation1');
        $storage->set('identifier2', 'participation2');

        // Act
        $result = $storage->all();

        // Assert
        $this->assertEquals([
            'identifier1' => 'participation1',
            'identifier2' => 'participation2',
        ], $result);
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::remove
     */
    public function testRemoveWithEmptyStorage()
    {
        // Arrange
        $storage = new RuntimeStorage();

        // Act
        $result = $storage->remove('identifier');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::remove
     */
    public function testRemoveWithFilledStorage()
    {
        // Arrange
        $storage = new RuntimeStorage();
        $storage->set('identifier', 'participation');

        // Act
        $result = $storage->remove('identifier');

        // Assert
        $this->assertEquals('participation', $result);
        $this->assertCount(0, $storage->all());
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::clear
     */
    public function testClearEmptyStorage()
    {
        // Arrange
        $storage = new RuntimeStorage();

        // Act
        $result = $storage->clear();

        // Assert
        $this->assertEquals([], $result);
    }

    /**
     * @covers PhpAb\Storage\RuntimeStorage::clear
     */
    public function testClearFilledStorage()
    {
        // Arrange
        $storage = new RuntimeStorage();
        $storage->set('identifier1', 'participation1');
        $storage->set('identifier2', 'participation2');

        // Act
        $result = $storage->clear();

        // Assert
        $this->assertEquals([
            'identifier1' => 'participation1',
            'identifier2' => 'participation2',
        ], $result);
        $this->assertCount(0, $storage->all());
    }
}
