<?php

namespace PhpAb\Storage;

use InvalidArgumentException;

/**
 * Stores the participation state of the user in a session.
 */
class Session implements StorageInterface
{
    /**
     * @var string The namespace of the session.
     */
    private $namespace;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $namespace The namespace of the session.
     */
    public function __construct($namespace)
    {
        if (!$namespace) {
            throw new InvalidArgumentException('The namespace is invalid.');
        }

        $this->namespace = $namespace;
    }

    /**
     * Gets the namespace that is used for this stroage.
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @inheritDoc
     */
    public function has($identifier)
    {
        if (empty($_SESSION[$this->getNamespace()])) {
            return false;
        }

        return !empty($_SESSION[$this->getNamespace()][$identifier]);
    }

    /**
     * @inheritDoc
     */
    public function get($identifier)
    {
        if (!$this->has($identifier)) {
            return null;
        }

        return $_SESSION[$this->getNamespace()][$identifier];
    }

    /**
     * @inheritDoc
     */
    public function set($identifier, $participation)
    {
        $_SESSION[$this->getNamespace()][$identifier] = $participation;
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        if (empty($_SESSION[$this->getNamespace()])) {
            return [];
        }

        return $_SESSION[$this->getNamespace()];
    }

    /**
     * @inheritDoc
     */
    public function remove($identifier)
    {
        if (!$this->has($identifier)) {
            return null;
        }

        $removedValue = $_SESSION[$this->getNamespace()][$identifier];

        unset($_SESSION[$this->getNamespace()][$identifier]);

        return $removedValue;
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        if (empty($_SESSION[$this->getNamespace()])) {
            return [];
        }

        $removedValues = $_SESSION[$this->getNamespace()];

        $_SESSION[$this->getNamespace()] = [];

        return $removedValues;
    }
}
