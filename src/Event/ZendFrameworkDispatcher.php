<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Event;

use Zend\EventManager\EventManager;

class ZendFrameworkDispatcher implements DispatcherInterface
{
    /**
     * The event manager used to dispatch events.
     *
     * @var EventManager
     */
    private $eventManager;

    /**
     * Initializes a new instance of this class.
     *
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * Gets the event manager.
     *
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @inheritdoc
     */
    public function dispatch($event, $options)
    {
        return $this->eventManager->trigger($event, $this, $options);
    }
}
