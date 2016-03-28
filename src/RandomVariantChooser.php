<?php

namespace PhpAb;
use PhpAb\Exception\ChoiceNotFoundException;

/**
 * Chooses a random variant
 */
class RandomVariantChooser implements VariantChooserInterface
{
    /**
     * @inheritDoc
     */
    public function chooseFrom($variants)
    {
        return array_rand($variants);
    }

}
