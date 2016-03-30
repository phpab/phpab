<?php

namespace Phpab\Phpab\Participation;

/**
 * Stores the participation state of the user
 */
interface StorageInterface
{
    /**
     * Checks if the test has a participation set.
     *
     * @param string $identifier The tests identifier
     *
     * @return bool true if the test participation is defined, false otherwise
     */
    public function has($identifier);

    /**
     * Returns the participation value (Variant or false).
     *
     * @param string $identifier The tests identifier name
     *
     * @return mixed
     */
    public function get($identifier);

    /**
     * Sets participation value for a test
     *
     * @param string $identifier The tests identifier
     * @param mixed  $participation The participated variant
     */
    public function set($identifier, $participation);

    /**
     * Returns all stored tests.
     *
     * @return array Attributes
     */
    public function all();

    /**
     * Removes a stored test.
     *
     * @param string $identifier
     *
     * @return mixed The removed value or null when it does not exist
     */
    public function remove($identifier);

    /**
     * Clears out state for a test.
     *
     * @return mixed Whatever data was contained.
     */
    public function clear();
}
