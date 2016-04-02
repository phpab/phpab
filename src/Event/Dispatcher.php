<?php

namespace PhpAb\Event;

class Dispatcher implements DispatcherInterface
{
    /**
     * @var array An array holding the Listeners for a given event.
     *            The format looks like
     *            [
     *              'eventname' => [callable, callable, callable],
     *              'eventname2' => [callable, callable, callable]
     *            ]
     */
    private $listeners = [];

    /**
     * @param          $eventName The name of the event to listen for
     * @param callable $callable  The Callable to execute once the event takes place
     */
    public function addListener($eventName, callable $callable)
    {
        $this->listeners[$eventName][] = $callable;
    }

    /**
     * @param \PhpAb\Event\SubscriberInterface $subscriber The subscriber which can subscribe to multiple events
     */
    public function addSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $callable) {
            $this->addListener($eventName, $callable);
        }
    }

    /**
     * @inheritdoc
     */
    public function dispatch($event, $options)
    {
        if (! array_key_exists($event, $this->listeners)) {
            // no callbacks given for this event
            return;
        }

        // Iterate through each Listener attached to this event
        // and call it with the given options
        foreach ($this->listeners[$event] as $callable) {
            call_user_func($callable, $options);
        }
    }
}
