<?php

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\Renderer\Google;

use PhpAb\Analytics\Renderer\RendererInterface;

/**
 * The base class for Google analytics implementations.
 *
 * @package PhpAb
 */
abstract class AbstractGoogleAnalytics implements RendererInterface
{
    /**
     * Gets the JavaScript that should be rendered.
     *
     * @param boolean $includeApiClient Whether or not to include the API Client too.
     *
     * @return string Returns the JavaScript code that should be rendered and the API client.
     */
    abstract public function getScript($includeApiClient);
}
