<?php

namespace PhpAb\Event;

/**
 * The Dispatcher dispatches/fires events that happens
 * in the application and dispatches the assigned callbacks.
 */
interface DispatcherInterface
{
    /**
     * Dispatches an event with some options
     *
     * @param $event The name of the Event which should be dispatched
     * @param $options The options that should get passed to the callback
     */
    public function dispatch($event, $options);
}
