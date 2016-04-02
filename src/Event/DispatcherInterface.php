<?php

namespace PhpAb\Event;

interface DispatcherInterface
{
    public function dispatch($event, $options);
}
