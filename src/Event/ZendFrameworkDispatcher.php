<?php

namespace PhpAb\Event;

use Zend\EventManager\EventManager;

class ZendFrameworkDispatcher implements DispatcherInterface
{
    /**
     * The event manager used to dispatch events.
     *
     * @var EventManager
     */
    private $eventManager;

    /**
     * Initializes a new instance of this class.
     *
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * Gets the event manager.
     *
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @inheritdoc
     */
    public function dispatch($event, $options)
    {
        return $this->eventManager->trigger($event, $this, $options);
    }
}
