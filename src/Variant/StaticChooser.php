<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2005-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant;

class StaticChooser implements ChooserInterface
{
    private $choice;

    public function __construct($choice)
    {
        $this->choice = $choice;
    }

    /**
     * @inheritDoc
     */
    public function chooseVariant($variants)
    {
        if (array_key_exists($this->choice, $variants)) {
            return $variants[$this->choice];
        }

        return null;
    }
}
