<?php

namespace PhpAb\Variant;

use PhpAb\Exception\TestExecutionException;

class SimpleVariantTest extends \PHPUnit_Framework_TestCase
{
    public function testGetIdentifier()
    {
        // Arrange
        $variant = new SimpleVariant('name');

        // Act
        $identifier = $variant->getIdentifier();

        // Assert
        $this->assertEquals('name', $identifier);
    }


    public function testRunReturnsNull()
    {
        // Arrange
        $variant = new SimpleVariant('name');

        // Act
        $result = $variant->run();

        // Assert
        $this->assertNull($result);
    }
}
