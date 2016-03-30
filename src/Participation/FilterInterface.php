<?php

namespace PhpAb\Participation;

/**
 * Filters out who should not participate
 */
interface FilterInterface
{
    /**
     * Checks if a user should participate in the test
     *
     * @return boolean
     */
    public function shouldParticipate();
}
