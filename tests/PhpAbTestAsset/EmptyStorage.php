<?php

namespace PhpAbTestAsset;

use PhpAb\TestInterface;
use PhpAb\Storage\StorageInterface;

class EmptyStorage implements StorageInterface
{
    private $choice;

    public function __construct($choice = null)
    {
        $this->choice = $choice;
    }

    public function clear(TestInterface $test)
    {
        $this->choice = null;
    }

    public function read(TestInterface $test)
    {
        return $this->choice;
    }

    public function write(TestInterface $test, $choice)
    {
        $this->choice = $choice;
    }
}
