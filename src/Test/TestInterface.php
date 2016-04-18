<?php

namespace PhpAb\Test;

use PhpAb\Variant\VariantInterface;

/**
 * The interface that should be implemented by all tests.
 */
interface TestInterface
{
    /**
     * Get the identifier for this test.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get all variants for this test.
     *
     * @return VariantInterface[]
     */
    public function getVariants();

    /**
     * Get a single variant for this test
     *
     * @param string $identifier The identifier of the variant to get.
     * @return VariantInterface|null
     */
    public function getVariant($identifier);
}
