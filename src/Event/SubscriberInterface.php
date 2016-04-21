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
