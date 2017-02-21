<?php

namespace PhpAb\Analytics;

class SimpleAnalytics implements AnalyticsInterface
{
    /**
     * @var array
     */
    private $particioations = [];

    /**
     * {@inheritDoc}
     */
    public function registerParticipation(Participation $participation)
    {
        $this->particioations[] = $participation;
    }

    /**
     * {@inheritDoc}
     */
    public function getParticipations()
    {
        return $this->particioations;
    }
}