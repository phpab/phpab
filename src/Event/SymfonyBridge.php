<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * An event dispatcher that acts as a bridge between phpab and Symfony.
 *
 * @package PhpAb
 */
class SymfonyBridge implements DispatcherInterface
{
    /**
     * The event dispatcher used to invoke events.
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Initializes a new instance of this class.
     *
     * @param EventDispatcherInterface $eventDispatcher The Symfony EventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $event The name of the Event which should be dispatched
     * @param array $options The options that should get passed to the callback
     */
    public function dispatch($event, $options)
    {
        $listeners = $this->eventDispatcher->getListeners($event);

        foreach ($listeners as $listener) {
            call_user_func($listener, $options);
        }
    }

    /**
     * Gets the original EventDispatcher
     *
     * @return EventDispatcherInterface
     */
    public function getOriginal()
    {
        return $this->eventDispatcher;
    }
}
