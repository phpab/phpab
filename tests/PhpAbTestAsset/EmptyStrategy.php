<?php

namespace PhpAbTestAsset;

use PhpAb\Participation\Strategy\StrategyInterface;
use PhpAb\RunnerInterface;

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

    public function isParticipating(RunnerInterface $runner)
    {
        return $this->participating;
    }
}
