<?php

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
