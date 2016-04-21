<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2005-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\Renderer;

abstract class AbstractGoogleAnalytics
{

    /**
     * @return string
     */
    abstract public function getScript();

    /**
     * @return array
     */
    abstract public function getParticipations();
}
