<?php

namespace Phpab\Phpab;

use Phpab\Phpab\Exception\TestExecutionException;
use Phpab\Phpab\TestDummy\DummyCallbackClass;

class CallbackVariantTest extends \PHPUnit_Framework_TestCase
{

    public function testGetIdentifier()
    {
        // Arrange
        $variant = new CallbackVariant('name', function() {});

        // Act
        $identifier = $variant->getIdentifier();

        // Assert
        $this->assertEquals('name', $identifier);
    }

    public function testRunWithSimpleClosure()
    {
        // Arrange
        $variant = new CallbackVariant('name', function() {
            return 'Walter';
        });

        // Act
        // Assert
        $this->assertEquals('Walter', $variant->run());
    }

    public function testRunWithCallbackAndDependencies()
    {
        // Arrange
        $callback = [
            new DummyCallbackClass('White'),
            'callbackMethod'
        ];

        $variant = new CallbackVariant('name', $callback);

        // Act
        // Assert
        $this->assertEquals('White', $variant->run());
    }

    /**
     * @expectedException \Phpab\Phpab\Exception\TestExecutionException
     */
    public function testExecutionThrowsException()
    {
        // Arrange
        $callback = [
            new DummyCallbackClass('White'),
            'failingCallbackMethod'
        ];

        $variant = new CallbackVariant('name', $callback);

        // Act
        $variant->run();
    }

}
