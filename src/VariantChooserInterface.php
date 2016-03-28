<?php

namespace PhpAb;
use PhpAb\Exception\ChoiceNotFoundException;

/**
 * Chooses
 */
interface VariantChooserInterface
{

    /**
     * @param callable[] $variants The variants to choose from
     *
     * @throws ChoiceNotFoundException
     * @return string the name of the chosen variant
     */
    public function chooseFrom($variants);

}
