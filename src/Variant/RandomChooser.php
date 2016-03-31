<?php

namespace PhpAb\Variant;

class RandomChooser implements ChooserInterface
{
    /**
     * @inheritDoc
     */
    public function chooseVariant($variants)
    {
        $chosen = array_rand($variants);

        return isset($variants[$chosen]) ? $variants[$chosen] : null;
    }
}
