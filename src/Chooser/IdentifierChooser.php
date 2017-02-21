<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link      https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license   https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Chooser;

use PhpAb\Variant\VariantInterface;

/**
 * A static choice implementation. The choice has been set by default already.
 *
 * @package PhpAb
 */
class IdentifierChooser implements ChooserInterface
{
    /**
     * The index of the variant to use.
     *
     * @var int
     */
    private $choice;

    /**
     * Initializes a new instance of this class.
     *
     * @param int $choice
     */
    public function __construct($choice)
    {
        $this->choice = $choice;
    }

    /**
     * {@inheritDoc}
     *
     * @param VariantInterface[] $variants Variants to choose from
     */
    public function chooseVariant($variants)
    {
        if (array_key_exists($this->choice, $variants)) {
            return $variants[$this->choice];
        }

        return null;
    }
}
