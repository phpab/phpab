<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant;

use PhpAb\Variant\VariantInterface;

/**
 * A VariantChooser is a class that chooses from n Variants
 * e.g. by Propability
 *
 * @package PhpAb
 */
interface ChooserInterface
{
    /**
     * Chooses the Variant from an array of Variants
     *
     * @param VariantInterface[] $variants Variants to choose from
     * @return VariantInterface|null the chosen Variant or null if none given
     */
    public function chooseVariant($variants);
}
