<?php

namespace Event;

use PhpAb\Event\Dispatcher;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testDispatchEventWithoutListeners()
    {
        // Arrange
        $dispatcher = new Dispatcher();

        // Act
        $result1 = $dispatcher->dispatch('event', null);
        $result2 = $dispatcher->dispatch('event2', null);

        // Assert
        $this->assertNull($result1);
        $this->assertNull($result2);
    }

    public function testDispatchWithSingleListener()
    {
        // Arrange
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('event.foo', function($subject) {
            $subject->executed = true;
        });

        $subject = new \stdClass();

        // Act
        $result = $dispatcher->dispatch('event.foo', $subject);

        // Assert
        $this->assertNull($result);
        $this->assertTrue($subject->executed);
    }

    public function testDispatchWithMultipleListenersOnOneEvent()
    {
        // Arrange
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('event.foo', function($subject) {
            $subject->touched++;
        });

        $dispatcher->addListener('event.foo', function($subject) {
            $subject->touched++;
        });

        $subject = new \stdClass();
        $subject->touched = 0;

        // Act
        $result = $dispatcher->dispatch('event.foo', $subject);

        // Assert
        $this->assertNull($result);
        $this->assertEquals(2, $subject->touched);
    }

    public function testDispatchMultipleEvents()
    {
        // Arrange
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('event.foo', function($subject) {
            $subject->touched++;
        });

        $dispatcher->addListener('event.bar', function($subject) {
            $subject->touched++;
        });

        $subject = new \stdClass();
        $subject->touched = 0;

        // Act
        $dispatcher->dispatch('event.foo', $subject);
        $dispatcher->dispatch('event.bar', $subject);

        // Assert
        $this->assertEquals(2, $subject->touched);
    }
}
