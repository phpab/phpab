<?php

namespace PhpAb\Test;

use PhpAb\Variant\SimpleVariant;
use PHPUnit_Framework_TestCase;

class TestTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers PhpAb\Test\Test::__construct
     * @covers PhpAb\Test\Test::getIdentifier
     */
    public function testConstructorAndGetIdentifierWithValidIdentifier()
    {
        // Arrange
        $test = new Test('identifier');

        // Act
        $result = $test->getIdentifier();

        // Assert
        $this->assertEquals('identifier', $result);
    }

    /**
     * @covers PhpAb\Test\Test::__construct
     * @covers PhpAb\Test\Test::getIdentifier
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The provided identifier is not a valid identifier.
     */
    public function testConstructorAndGetIdentifierWithInvalidIdentifier()
    {
        // Arrange
        // ...

        // Act
        $test = new Test(null);

        // Assert
        // ...
    }

    /**
     * @covers PhpAb\Test\Test::__construct
     * @covers PhpAb\Test\Test::getVariants
     */
    public function testConstructorAndGetVariantsWithVariants()
    {
        // Arrange
        $variant = new SimpleVariant('identifier');
        $test = new Test('identifier', [
            $variant,
        ]);

        // Act
        $result = $test->getVariants();

        // Assert
        $this->assertEquals([
            'identifier' => $variant
        ], $result);
    }

    /**
     * @covers PhpAb\Test\Test::addVariant
     */
    public function testAddVariant()
    {
        // Arrange
        $variant = new SimpleVariant('identifier');
        $test = new Test('identifier');

        // Act
        $test->addVariant($variant);

        // Assert
        $this->assertEquals([
            'identifier' => $variant
        ], $test->getVariants());
    }

    /**
     * @covers PhpAb\Test\Test::addVariant
     * @expectedException PhpAb\Exception\DuplicateVariantException
     * @expectedExceptionMessage A variant with this identifier has already been added.
     */
    public function testAddVariantWithDuplicateIdentifier()
    {
        // Arrange
        $variant = new SimpleVariant('identifier');
        $test = new Test('identifier');

        // Act
        $test->addVariant($variant);
        $test->addVariant($variant);

        // Assert
        // ...
    }

    /**
     * @covers PhpAb\Test\Test::setVariants
     */
    public function testSetVariantsWithEmptyArray()
    {
        // Arrange
        $test = new Test('identifier');

        // Act
        $test->setVariants([]);

        // Assert
        $this->assertEquals([], $test->getVariants());
    }

    /**
     * @covers PhpAb\Test\Test::setVariants
     */
    public function testSetVariantsWithSingleVariant()
    {
        // Arrange
        $variant1 = new SimpleVariant('identifier1');
        $test = new Test('identifier');

        // Act
        $test->setVariants([$variant1]);

        // Assert
        $this->assertEquals([
            'identifier1' => $variant1,
        ], $test->getVariants());
    }

    /**
     * @covers PhpAb\Test\Test::setVariants
     */
    public function testSetVariantsWithMultipleVariant()
    {
        // Arrange
        $variant1 = new SimpleVariant('identifier1');
        $variant2 = new SimpleVariant('identifier2');
        $test = new Test('identifier');

        // Act
        $test->setVariants([$variant1, $variant2]);

        // Assert
        $this->assertEquals([
            'identifier1' => $variant1,
            'identifier2' => $variant2,
        ], $test->getVariants());
    }

    /**
     * @covers PhpAb\Test\Test::setVariants
     * @expectedException PhpAb\Exception\DuplicateVariantException
     * @expectedExceptionMessage A variant with this identifier has already been added.
     */
    public function testSetVariantsWithDuplicateVariants()
    {
        // Arrange
        $variant1 = new SimpleVariant('identifier1');
        $variant2 = new SimpleVariant('identifier1');
        $test = new Test('identifier');

        // Act
        $test->setVariants([$variant1, $variant2]);

        // Assert
        // ...
    }

    /**
     * @covers PhpAb\Test\Test::getVariant
     */
    public function testGetVariant()
    {
        // Arrange
        $variant1 = new SimpleVariant('identifier1');
        $test = new Test('identifier', [$variant1]);

        // Act
        $result = $test->getVariant('identifier1');

        // Assert
        $this->assertEquals($variant1, $result);
    }

    /**
     * @covers PhpAb\Test\Test::getVariant
     */
    public function testGetVariantWithInvalidIdentifier()
    {
        // Arrange
        $test = new Test('identifier');

        // Act
        $result = $test->getVariant('identifier1');

        // Assert
        $this->assertNull($result);
    }
}
