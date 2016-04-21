<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Event;

/**
 * A simple event dispatcher that triggers the attached listeners.
 *
 * @package PhpAb
 */
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
     * Adds the given callback to this dispatcher so that it listens to the given event name.
     *
     * @param string   $eventName The name of the event to listen for
     * @param callable $callable  The Callable to execute once the event takes place
     */
    public function addListener($eventName, callable $callable)
    {
        $this->listeners[$eventName][] = $callable;
    }

    /**
     * Adds a subscriber to this dispatcher which in its turn adds all the subscribed events.
     *
     * @param SubscriberInterface $subscriber The subscriber which can subscribe to multiple events
     */
    public function addSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $callable) {
            $this->addListener($eventName, $callable);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param string $event The name of the Event which should be dispatched
     * @param array $options The options that should get passed to the callback
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
