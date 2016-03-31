<?php

namespace PhpAb\Storage;

use InvalidArgumentException;
use RuntimeException;

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
     * @param bool $startSession Whether or not to start the session if it hasn't been started yet.
     */
    public function __construct($namespace)
    {
        // We cannot typehint for primitive types yet so therefor we check if the namespace is a (valid) string.
        if (!is_string($namespace) || $namespace === '') {
            throw new InvalidArgumentException('The namespace is invalid.');
        }

        if (session_status() === PHP_SESSION_NONE) {
            throw new RuntimeException('The session has not been started.');
        }

        $this->namespace = $namespace;
    }

    /**
     * Gets the namespace that is used for this stroage.
     */
    private function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @inheritDoc
     */
    public function has($identifier)
    {
        if (!array_key_exists($this->getNamespace(), $_SESSION)) {
            return false;
        }

        return array_key_exists($identifier, $_SESSION[$this->getNamespace()]);
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
        if (!array_key_exists($this->getNamespace(), $_SESSION)) {
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
        if (!array_key_exists($this->getNamespace(), $_SESSION)) {
            return [];
        }

        $removedValues = $_SESSION[$this->getNamespace()];

        $_SESSION[$this->getNamespace()] = [];

        return $removedValues;
    }
}
