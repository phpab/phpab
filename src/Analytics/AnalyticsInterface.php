<?php

namespace PhpAb\Analytics;

/**
 * Collects some participation data
 */
interface AnalyticsInterface
{
    /**
     * @param \PhpAb\Analytics\Participation $participation
     */
    public function registerParticipation(Participation $participation);

    /**
     * @return array
     */
    public function getParticipations();
}
