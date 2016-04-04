<?php

namespace PhpAb\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SymfonyBridge implements DispatcherInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher The Symfony EventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritDoc
     */
    public function dispatch($event, $options)
    {
        $listeners = $this->eventDispatcher->getListeners($event);

        foreach ($listeners as $listener) {
            call_user_func($listener, $options);
        }
    }
}
