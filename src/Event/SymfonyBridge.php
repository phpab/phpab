<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2005-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

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

    /**
     * Gets the original EventDispatcher
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public function getOriginal()
    {
        return $this->eventDispatcher;
    }
}
