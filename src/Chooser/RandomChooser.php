<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Chooser;

use PhpAb\Variant\VariantInterface;

/**
 * A variant chooser that makes its choice randomly.
 *
 * @package PhpAb
 */
class RandomChooser implements ChooserInterface
{
    /**
     * {@inheritDoc}
     *
     * @param VariantInterface[] $variants Variants to choose from
     */
    public function chooseVariant($variants)
    {
        $count = count($variants);
        if (0 === $count) {
            return null;
        }

        $chosenCount = mt_rand(0, $count - 1);
        $keys = array_keys($variants);

        return $variants[$keys[$chosenCount]];
    }
}
