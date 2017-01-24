<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

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
     * @test
     * @expectedException PhpAb\Exception\DuplicateVariantException
     * @expectedExceptionMessage A variant with this identifier has already been added.
     */
    public function add_two_variants_with_the_same_id_should_throw_an_exception()
    {
        // Arrange
        $variant = new SimpleVariant('identifier');

        // Act
        $test = new Test('identifier', [$variant, $variant]);

        // Assert
        // ...
    }

    /**
     * @test
     */
    public function testSetVariantsWithEmptyArray()
    {
        // Arrange
        $variants = [];

        // Act
        $test = new Test('identifier', $variants);

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

        // Act
        $test = new Test('identifier', [$variant1]);

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

        // Act
        $test = new Test('identifier', [$variant1, $variant2]);

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

        // Act
        $test = new Test('identifier', [$variant1, $variant2]);

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

    /**
     * Testing that options passed in constructor are returned by getOptions
     */
    public function testGetOptions()
    {
        // Arrange
        $options = [
            'key1' => 'val1',
            'key2' => 'val2'
        ];

        $test = new Test('identifier', [], $options);

        // Act
        $result = $test->getOptions();

        // Assert
        $this->assertSame($options, $result);
    }
}
