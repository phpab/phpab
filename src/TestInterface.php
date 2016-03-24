<?php

namespace PhpAb;

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
     * @param $identifier
     * @return callable
     */
    public function getCallback($identifier);

    /**
     * Gets the participation strategy.
     *
     * @return StrategyInterface
     */
    public function getParticipationStrategy();
}
