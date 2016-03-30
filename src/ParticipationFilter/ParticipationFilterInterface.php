<?php

namespace Phpab\Phpab\ParticipationStrategy;

/**
 * Filters out who should not participate
 */
interface ParticipationFilterInterface
{

    /**
     * Checks if a user should participate in the test
     *
     * @return boolean
     */
    public function shouldParticipate();
}
