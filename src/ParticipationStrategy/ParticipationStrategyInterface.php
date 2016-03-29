<?php

/**
 * @license MIT License
 * @copyright  2016 Phpab Development Team
 */

namespace Phpab\Phpab\ParticipationStrategy;

/**
 * 
 */
interface ParticipationStrategyInterface
{

    /**
     * Checks if a user should participate in the test
     *
     * @return boolean
     */
    public function shouldParticipate();
}
