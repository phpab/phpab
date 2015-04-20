<?php

namespace PhpAbTestAsset;

use PhpAb\AbRunner;
use PhpAb\Participation\Strategy\StrategyInterface;

class EmptyStrategy implements StrategyInterface
{
    private $participating;

    public function __construct($participating = true)
    {
        $this->participating = $participating;
    }

    public function setParticipating($participating)
    {
        $this->participating = $participating;
    }

    public function isParticipating(AbRunner $runner)
    {
        return $this->participating;
    }
}
