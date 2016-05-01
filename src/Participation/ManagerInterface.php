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
interface ManagerInterface
{
    /**
     * Gets the variant the user is participating in for the given test.
     *
     * @param TestInterface|string $test The identifier of the test to get the variant for.
     * @return string|null Returns the identifier of the variant or null if not participating.
     */
    public function getParticipatingVariant($test);

    /**
     * Check if the user participates in a test or a specific variant of the test
     *
     * @param TestInterface|string $test The identifier of the test to check.
     * @param VariantInterface|string|null $variant The identifier of the variant to check
     * @return boolean|string Returns true when the user participates; false otherwise.
     */
    public function participates($test, $variant = null);

    /**
     * Sets the participation to a test with the participation at a specific variant.
     *
     * @param TestInterface|string $test The identifier of the test that should be participated.
     * @param VariantInterface|string|null $variant The identifier of the variant that was chosen or
     * null if the user does not participate in the test.
     */
    public function participate($test, $variant);
}
