<?php

namespace PhpAb\Analytics;

class SimpleAnalytics implements AnalyticsInterface
{
    /**
     * @var array
     */
    private $participations = [];

    /**
     * {@inheritDoc}
     */
    public function registerParticipation(Participation $participation)
    {
        $this->participations[] = $participation;
    }

    /**
     * {@inheritDoc}
     */
    public function getParticipations()
    {
        return $this->participations;
    }
}
