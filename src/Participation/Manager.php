<?php

namespace PhpAb\Participation;

use PhpAb\Storage\StorageInterface;

class Manager implements ParticipationManagerInterface
{
    /**
     * @var \PhpAb\Storage\StorageInterface
     */
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function participates($test, $variant = null)
    {
        // Todo: implement methods
        return false;
    }

    /**
     * @inheritDoc
     */
    public function participate($test, $variant)
    {
        // TODO: Implement participate() method.
    }
}
