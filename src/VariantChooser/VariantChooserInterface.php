<?php

/**
 * @license MIT License
 * @copyright  2016 Phpab Development Team
 */

namespace Phpab\Phpab\VariantChooser;

/**
 * 
 */
interface VariantChooserInterface
{

    // Chooses a Variant according to its implementation
    public function chooseVariant();
}
