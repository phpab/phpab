<?php

namespace PhpAb;

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
}
