<?php

namespace PhpAb\Test;

use PhpAb\Variant\VariantInterface;

interface TestInterface
{
    /**
     * Get the identifier for this test
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get all variants for this test
     *
     * @return VariantInterface[]
     */
    public function getVariants();

    /**
     * Get a single variant for this test
     *
     * @param string $variant The variants Identifier
     *
     * @return VariantInterface
     */
    public function getVariant($variant);
}
