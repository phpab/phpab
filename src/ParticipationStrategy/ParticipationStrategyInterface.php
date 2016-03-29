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

    // Given an implementation, establishes if User participates
    // in current Test
    public function isParticipant();
}
