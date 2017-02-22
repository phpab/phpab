<?php

namespace PhpAb\Analytics;

use PhpAb\Test\Test;
use PhpAb\Variant\SimpleVariant;
use PHPUnit\Framework\TestCase;

class ParticipationTest extends TestCase
{

    /**
     * @test
     */
    public function getters()
    {
        $test = new Test('t1');
        $variant = new SimpleVariant('v1');

        $participation = new Participation($test, $variant, ['a' => 'b']);

        $this->assertEquals($test, $participation->getTest());
        $this->assertEquals($variant, $participation->getVariant());
        $this->assertEquals(['a' => 'b'], $participation->getOptions());
    }

}