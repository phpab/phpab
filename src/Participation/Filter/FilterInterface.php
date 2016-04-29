<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Participation\Filter;

/**
 * The interface that should be implemented by filters that decide who should or should not participate.
 *
 * @package PhpAb
 */
interface FilterInterface
{
    /**
     * Checks if a user should participate in the test
     *
     * @return boolean Returns true when the user should participate; false otherwise.
     */
    public function shouldParticipate();
}
