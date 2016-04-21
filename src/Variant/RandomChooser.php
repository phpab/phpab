<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2005-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant;

class RandomChooser implements ChooserInterface
{
    /**
     * @inheritDoc
     */
    public function chooseVariant($variants)
    {
        $count = count($variants);
        if (0 === $count) {
            return null;
        }

        $chosenCount = mt_rand(0, $count-1);
        $keys = array_keys($variants);
        
        return $variants[$keys[$chosenCount]];
    }
}
