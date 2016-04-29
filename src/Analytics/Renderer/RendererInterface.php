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
 * The interface that should be implemented by analytics providers that require JavaScript to be rendered.
 *
 * @package PhpAb
 */
interface RendererInterface
{
    /**
     * Gets the list with tests that the user participates in.
     *
     * @return array Returns the map with participations.
     */
    public function getParticipations();
}
