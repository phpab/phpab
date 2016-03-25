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
     * Gets the Callback by it's identifier
     *
     * @throws ChoiceNotFoundException
     *
     * @param string $choice
     * @return callable
     */
    public function getCallback($choice);

    /**
     * Gets the participation strategy.
     *
     * @return StrategyInterface
     */
    public function getParticipationStrategy();

    /**
     * Chooses one variation/option and returns the name
     *
     * @return string The chosen variation
     */
    public function choose();
}
