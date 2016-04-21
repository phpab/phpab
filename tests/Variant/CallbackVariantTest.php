<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2005-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant;

use PhpAb\Exception\TestExecutionException;

class CallbackVariantTest extends \PHPUnit_Framework_TestCase
{
    public function testGetIdentifier()
    {
        // Arrange
        $variant = new CallbackVariant('name', function () {
        });

        // Act
        $identifier = $variant->getIdentifier();

        // Assert
        $this->assertEquals('name', $identifier);
    }

    public function testRunExecutesCallback()
    {
        // Arrange
        $action = null;
        $variant = new CallbackVariant('name', function () use ($action) {
            $action = 'Walter';
            return $action;
        });

        // Act
        // Assert
        $this->assertNull($variant->run());
    }

    /**
     * @expectedException \PhpAb\Exception\TestExecutionException
     */
    public function testRunClosureThrowsException()
    {
        // Arrange
        $variant = new CallbackVariant('name', function () {
            throw new TestExecutionException();
        });

        // Act
        $variant->run();
    }

    public function testRunReturnsNull()
    {
        // Arrange
        $variant = new CallbackVariant('name', function () {
            return 'Walter';
        });

        // Act
        $result = $variant->run();

        // Assert
        $this->assertNull($result);
    }
}
