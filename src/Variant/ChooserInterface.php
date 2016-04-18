<?php

namespace PhpAb\Variant;

use PhpAb\Variant\VariantInterface;

/**
 * A VariantChooser is a class that chooses from n Variants
 * e.g. by Propability
 */
interface ChooserInterface
{
    /**
     * Chooses the Variant from an array of Variants
     *
     * @param VariantInterface[] $variants Variants to choose from
     *
     * @return VariantInterface|null the chosen Variant or null if none given
     */
    public function chooseVariant($variants);
}
