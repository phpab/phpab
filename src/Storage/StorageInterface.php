<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage;

/**
 * Stores the participation state of the user
 *
 * @package PhpAb
 */
interface StorageInterface
{
    /**
     * Checks if the test has a participation set.
     *
     * @param string $identifier The tests identifier
     * @throws InvalidArgumentException
     * @return bool true if the test participation is defined, false otherwise
     */
    public function has($identifier);

    /**
     * Returns the participation value (Variant or false).
     *
     * @param string $identifier The tests identifier name
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function get($identifier);

    /**
     * Sets participation value for a test
     *
     * @param string $identifier The tests identifier
     * @param mixed  $participation The participated variant
     * @throws InvalidArgumentException
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
     * @param string $identifier The identifier of the test to remove.
     * @throws InvalidArgumentException
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
