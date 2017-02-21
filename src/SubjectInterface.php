<?php

namespace PhpAb;

use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;

interface SubjectInterface
{
    /**
     * Check if the user participates in a test or a specific variant of the test
     *
     * @param TestInterface|string $test The identifier of the test to check.
     * @param VariantInterface|string|null $variant The identifier of the variant to check
     * @return boolean|string Returns true when the user participates; false otherwise.
     */
    public function participates(TestInterface $test, VariantInterface $variant = null);

    /**
     * Sets the participation to a test with the participation at a specific variant.
     *
     * @param TestInterface|string $test The identifier of the test that should be participated.
     * @param VariantInterface|null $variant The identifier of the variant that was chosen.
     *                                       null if the subject should just participate in the test
     */
    public function participate(TestInterface $test, VariantInterface $variant = null);

    /**
     * Check if the participation is blocked for a given test
     *
     * @param \PhpAb\Test\TestInterface $test
     *
     * @return boolean
     */
    public function participationIsBlocked(TestInterface $test);

    /**
     * Block the participation for a given test
     *
     * @param \PhpAb\Test\TestInterface $test
     *
     * @return null
     */
    public function blockParticipationFor(TestInterface $test);
}
