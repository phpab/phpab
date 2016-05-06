<?php

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\Renderer\Google;

use PhpAb\Analytics\Renderer\JavascriptRendererInterface;

/**
 * The base class for Google analytics implementations.
 *
 * @package PhpAb
 */
abstract class AbstractGoogleAnalytics implements JavascriptRendererInterface
{
    /**
     * @var bool Whether or not to include the Api Client
     */
    private $includeApiClient = false;

    /**
     * @param bool $includeApiClient Whether or not to include the Api Client
     */
    public function setApiClientInclusion($includeApiClient = false)
    {
        $this->includeApiClient = true === $includeApiClient;
    }

    /**
     * @return bool The value of $includeApiClient
     */
    public function getApiClientInclusion()
    {
        return $this->includeApiClient;
    }
}
