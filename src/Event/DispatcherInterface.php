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
