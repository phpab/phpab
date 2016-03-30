<?php

namespace Phpab\Phpab\Storage;

/**
 * Stores the participation state of the user
 */
interface ParticipationStorageInterface
{
    /**
     * Checks if the test has a participation set.
     *
     * @param string $identifier The attribute name
     *
     * @return bool true if the attribute is defined, false otherwise
     */
    public function has($identifier);

    /**
     * Returns the participation value (Variant or false).
     *
     * @param string $identifier The attribute name
     *
     * @return mixed
     */
    public function get($identifier);

    /**
     * Sets an attribute.
     *
     * @param string $identifier
     * @param mixed  $participation The participated variant
     */
    public function set($identifier, $participation);

    /**
     * Returns attributes.
     *
     * @return array Attributes
     */
    public function all();

    /**
     * Removes an attribute.
     *
     * @param string $identifier
     *
     * @return mixed The removed value or null when it does not exist
     */
    public function remove($identifier);
}
