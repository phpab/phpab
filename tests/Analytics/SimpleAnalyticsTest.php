<?php

namespace PhpAb\Analytics;

use PhpAb\Test\Test;
use PhpAb\Variant\SimpleVariant;
use PHPUnit\Framework\TestCase;

class SimpleAnalyticsTest extends TestCase
{

    /**
     * @test
     */
    public function register_participation()
    {
        $analytics = new SimpleAnalytics();

        $analytics->registerParticipation(new Participation(new Test('t1'), new SimpleVariant('v1')));

        $this->assertCount(1, $analytics->getParticipations());
    }

    public function register_multiple_participations()
    {
        $analytics = new SimpleAnalytics();

        $analytics->registerParticipation(new Participation(new Test('t1'), new SimpleVariant('v1')));
        $analytics->registerParticipation(new Participation(new Test('t2'), new SimpleVariant('v1')));

        $this->assertCount(2, $analytics->getParticipations());
    }
}
