<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage;

use InvalidArgumentException;
use RuntimeException;

/**
 * Stores the participation state of the user in a session.
 *
 * @package PhpAb
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
        // We cannot typehint for primitive types yet so therefor we check if the namespace is a (valid) string.
        if (!is_string($namespace) || $namespace === '') {
            throw new InvalidArgumentException('The namespace is invalid.');
        }

        if (PHP_SESSION_NONE === session_status()) {
            session_start();
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
     * {@inheritDoc}
     *
     * @param string $identifier The tests identifier
     */
    public function has($identifier)
    {
        if (!array_key_exists($this->getNamespace(), $_SESSION)) {
            return false;
        }

        return array_key_exists($identifier, $_SESSION[$this->getNamespace()]);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $identifier The tests identifier name
     */
    public function get($identifier)
    {
        if (!$this->has($identifier)) {
            return null;
        }

        return $_SESSION[$this->getNamespace()][$identifier];
    }

    /**
     * {@inheritDoc}
     *
     * @param string $identifier The tests identifier
     * @param mixed  $participation The participated variant
     */
    public function set($identifier, $participation)
    {
        $_SESSION[$this->getNamespace()][$identifier] = $participation;
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        if (!array_key_exists($this->getNamespace(), $_SESSION)) {
            return [];
        }

        return $_SESSION[$this->getNamespace()];
    }

    /**
     * {@inheritDoc}
     *
     * @param string $identifier The identifier of the test to remove.
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
     * {@inheritDoc}
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
