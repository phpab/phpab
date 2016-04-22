<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Participation;

/**
 * The interface that should be implemented by participation managers.
 *
 * @package PhpAb
 */
interface ParticipationManagerInterface
{
    /**
     * Check if the User participates in a test or a specific variant of the test
     *
     * @param string $test The identifier of the test to check.
     * @param string|null $variant The identifier of the variant to check
     *
     * @return boolean|string Returns false if there is no participation.
     * Returns a string for the participated variant if participating.
     * Returns true if explicit variant was asked and matches
     */
    public function participates($test, $variant = null);

    /**
     * Sets the participation to a test with the participation at a specific variant.
     *
     * @param string $test The identifier of the test that should be participated.
     * @param string|false $variant The identifier of the variant that was chosen for the user
     * or false if the user should not participate at the test.
     */
    public function participate($test, $variant);
}
