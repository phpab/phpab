<?php

namespace PhpAb\Event;

/**
 * The Subscriber can listen to multiple events at once.
 */
interface SubscriberInterface
{
    public function getSubscribedEvents();
}
