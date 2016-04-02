<?php

namespace PhpAb\Event;

class Dispatcher implements DispatcherInterface
{
    private $listeners = [];

    public function addListener($eventName, callable $callable)
    {
        $this->listeners[$eventName][] = $callable;
    }

    public function addSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $callable) {
            $this->addListener($eventName, $callable);
        }
    }

    public function dispatch($event, $options)
    {
        if (! array_key_exists($event, $this->listeners)) {
            // no callbacks given for this event
            return;
        }

        foreach ($this->listeners[$event] as $callable) {
            call_user_func($callable, $options);
        }
    }
}
