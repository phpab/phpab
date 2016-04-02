<?php

namespace PhpAb\Participation;

interface ParticipationManagerInterface
{
    /**
     * Check if the User participates in a test
     * or a specific Variant of the test
     *
     * @param string      $test    The identifier of the test to check
     * @param string|null $variant The identifier of the variant to check
     *
     * @return boolean
     */
    public function participates($test, $variant = null);

    /**
     * Sets the participation to a test with the participation
     * at a specific variant.
     *
     * @param string       $test    The identifier of the test that should be participated.
     * @param string|false $variant The identifier of the variant that was chosen for the user.
     *                              Or false if the user should not participate at the test.
     */
    public function participate($test, $variant);
}
