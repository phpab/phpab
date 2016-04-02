<?php

namespace PhpAb\Event;

/**
 * Interface SubscriberInterface
 *
 * The Subscriber can listen to multiple events at once.
 */
interface SubscriberInterface
{
    public function getSubscribedEvents();
}
