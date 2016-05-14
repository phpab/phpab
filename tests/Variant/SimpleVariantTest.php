<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant;

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
