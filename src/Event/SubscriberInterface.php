<?php

namespace PhpAb\Event;

/**
 * The Subscriber can listen to multiple events at once.
 */
interface SubscriberInterface
{
    /**
     * @return array The array of events it subscribes to
     *               The format looks like
     *               [
     *                 'eventname' => callable,
     *                 'eventname2' => callable
     *               ]
     */
    public function getSubscribedEvents();
}
