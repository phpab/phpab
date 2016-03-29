<?php

namespace Phpab\Phpab\VariantChooser;

/**
 * A VariantChooser is a class that chooses from n Variants
 * e.g. by Propability
 */
interface VariantChooserInterface
{

    // Chooses a Variant according to its implementation
    public function chooseVariant();
}
