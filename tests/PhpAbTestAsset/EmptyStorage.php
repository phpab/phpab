<?php

namespace PhpAbTestAsset;

use PhpAb\AbTest;
use PhpAb\Storage\StorageInterface;

class EmptyStorage2 implements StorageInterface
{
    private $choice;

    public function __construct($choice = null)
    {
        $this->choice = $choice;
    }

    public function clear(AbTest $abTest)
    {
        $this->choice = null;
    }

    public function read(AbTest $abTest)
    {
        return $this->choice;
    }

    public function write(AbTest $abTest, $choice)
    {
        $this->choice = $choice;
    }
}
