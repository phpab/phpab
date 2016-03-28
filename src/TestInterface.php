<?php

namespace PhpAb;

use PhpAb\Exception\ChoiceNotFoundException;
use PhpAb\Participation\Strategy\StrategyInterface;

interface TestInterface
{
    /**
     * Gets the name of this test.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the control variant
     *
     * @return callable
     */
    public function getControlVariant();

    /**
     * Gets the Variant by it's identifier
     *
     * @throws ChoiceNotFoundException
     *
     * @param string $variant
     * @return callable
     */
    public function getVariant($variant);

    /**
     * Get all Variants for the test
     *
     * @return callable[]
     */
    public function getVariants();

    /**
     * Gets the participation strategy.
     *
     * @return StrategyInterface
     */
    public function getParticipationStrategy();

    /**
     * Gets the Chooser which is responsible for choosing
     * the variant for the new user.
     *
     * @return VariantChooserInterface
     */
    public function getVariantChooser();
}
