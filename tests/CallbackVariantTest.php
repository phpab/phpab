<?php

namespace Phpab\Phpab;

use Phpab\Phpab\Exception\TestExecutionException;
use Phpab\Phpab\TestDummy\DummyCallbackClass;

class CallbackVariantTest extends \PHPUnit_Framework_TestCase
{

    public function testGetIdentifier()
    {
        // Arrange
        $variant = new CallbackVariant('name', function () {});

        // Act
        $identifier = $variant->getIdentifier();

        // Assert
        $this->assertEquals('name', $identifier);
    }

    public function testRunWithSimpleClosure()
    {
        // Arrange
        $variant = new CallbackVariant('name', function () {
            return 'Walter';
        });

        // Act
        // Assert
        $this->assertEquals('Walter', $variant->run());
    }

    /**
     * @expectedException \Phpab\Phpab\Exception\TestExecutionException
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
}
