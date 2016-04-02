<?php

namespace PhpAb\Storage;

use InvalidArgumentException;
use RuntimeException;

/**
 * Stores the participation state of the user only for the current request.
 */
class Runtime implements StorageInterface
{
    /**
     * @var array The data that has been set.
     */
    private $data;

    /**
     * Initializes a new instance of this class.
     */
    public function __construct()
    {
        $this->data = [];
    }

    /**
     * @inheritDoc
     */
    public function has($identifier)
    {
        return array_key_exists($identifier, $this->data);
    }

    /**
     * @inheritDoc
     */
    public function get($identifier)
    {
        if (!$this->has($identifier)) {
            return null;
        }

        return $this->data[$identifier];
    }

    /**
     * @inheritDoc
     */
    public function set($identifier, $participation)
    {
        $this->data[$identifier] = $participation;
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function remove($identifier)
    {
        if (!$this->has($identifier)) {
            return null;
        }

        $removedValue = $this->data[$identifier];

        unset($this->data[$identifier]);

        return $removedValue;
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $removedValues = $this->data;

        $this->data = [];

        return $removedValues;
    }
}
