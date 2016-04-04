<?php

namespace PhpAb\Event;

use Symfony\Component\EventDispatcher\EventDispatcher;

class SymfonyBridgeTest extends \PHPUnit_Framework_TestCase
{
    private $dispatcher;

    public function setUp()
    {
        $this->dispatcher = new Dispatcher(new EventDispatcher());
    }

    public function testDispatchEventWithoutListeners()
    {
        // Arrange
        $dispatcher = $this->dispatcher;

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
        $dispatcher = $this->dispatcher;
        $dispatcher->addListener('event.foo', function ($subject) {
            $subject->executed = true;
            return 'yolo';
        });

        $subject = new \stdClass();

        // Act
        $result = $dispatcher->dispatch('event.foo', $subject);

        // Assert
        $this->assertNull($result);
        $this->assertTrue($subject->executed);
    }

    public function testDispatchSubscriberNotAllDispatched()
    {
        // Arrange
        $callable = function ($subject) {
            $subject->touched++;
        };

        $subscriber = $this->getMock(SubscriberInterface::class);
        $subscriber
            ->method('getSubscribedEvents')
            ->willReturn([
                'event.foo' => $callable,
                'event.bar' => $callable,
            ]);

        $dispatcher = $this->dispatcher;
        $dispatcher->addSubscriber($subscriber);

        $subject = new \stdClass();
        $subject->touched = 0;

        // Act
        $dispatcher->dispatch('event.foo', $subject);

        // Assert
        $this->assertEquals(1, $subject->touched);
    }

    public function testDispatchWithMultipleListenersOnOneEvent()
    {
        // Arrange
        $dispatcher = $this->dispatcher;
        $dispatcher->addListener('event.foo', function ($subject) {
            $subject->touched++;
        });

        $dispatcher->addListener('event.foo', function ($subject) {
            $subject->touched++;
        });

        $subject = new \stdClass();
        $subject->touched = 0;

        // Act
        $dispatcher->dispatch('event.foo', $subject);

        // Assert
        $this->assertEquals(2, $subject->touched);
    }

    public function testDispatchMultipleEvents()
    {
        // Arrange
        $dispatcher = $this->dispatcher;
        $dispatcher->addListener('event.foo', function ($subject) {
            $subject->touched++;
        });

        $dispatcher->addListener('event.bar', function ($subject) {
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
