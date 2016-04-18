<?php

namespace PhpAb\Event;

use PHPUnit_Framework_TestCase;
use Zend\EventManager\EventManager;

class ZendFrameworkDispatcherTest extends PHPUnit_Framework_TestCase
{
    public function testGetEventManager()
    {
        // Arrange
        $eventManager = new EventManager();
        $dispatcher = new ZendFrameworkDispatcher($eventManager);

        // Act
        $result = $dispatcher->getEventManager();

        // Assert
        $this->assertEquals($eventManager, $result);
    }

    public function testDispatch()
    {
        // Arrange
        $eventManager = $this->getMock(EventManager::class);
        $dispatcher = new ZendFrameworkDispatcher($eventManager);

        // Assert
        $eventManager->expects($this->once())->method('trigger')->with(
            $this->equalTo('event.foo'),
            $this->equalTo($dispatcher),
            $this->equalTo([])
        );

        // Act
        $dispatcher->dispatch('event.foo', []);
    }
}
