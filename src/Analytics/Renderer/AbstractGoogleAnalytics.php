<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\Renderer;

/**
 * The base class for Google analytics implementations.
 *
 * @package PhpAb
 */
abstract class AbstractGoogleAnalytics
{
    /**
     * Gets the JavaScript that should be rendered.
     *
     * @return string Returns the JavaScript code that should be rendered.
     */
    abstract public function getScript();

    /**
     * Gets the list with tests that the user participates in.
     *
     * @return array Returns the map with participations.
     */
    abstract public function getParticipations();
}
