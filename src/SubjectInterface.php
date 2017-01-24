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
     * @param VariantInterface $variant The identifier of the variant that was chosen
     */
    public function participate(TestInterface $test, VariantInterface $variant);

    public function participationIsBlocked(TestInterface $test);

    public function blockParticipationFor(TestInterface $test);
}
