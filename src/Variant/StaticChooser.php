<?php

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
