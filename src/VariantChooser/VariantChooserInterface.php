<?php

namespace Phpab\Phpab\VariantChooser;
use Phpab\Phpab\VariantInterface;

/**
 * A VariantChooser is a class that chooses from n Variants
 * e.g. by Propability
 */
interface VariantChooserInterface
{

    /**
     * Chooses the Variant from an array of Variants
     *
     * @param VariantInterface[] $variants Variants to choose from
     *
     * @return VariantInterface the chosen Variant
     */
    public function chooseVariant($variants);
}
