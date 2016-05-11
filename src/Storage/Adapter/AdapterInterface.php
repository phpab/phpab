<?php

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage\Adapter;

/**
 * Used by StorageInterface to store user participations state
 *
 * @package PhpAb
 */
interface AdapterInterface
{

    /**
     * Returns if a string identified element exists
     *
     * @param string $identifier Element identifier
     *
     * @return bool If element exists
     */
    public function has($identifier);

    /**
     * Returns the value of a string identified element
     *
     * @param mixed $identifier Element identifier
     *
     * @return mixed The value of element
     */
    public function get($identifier);

    /**
     * Sets the value of a string identified element
     *
     * @param string $identifier Element identifier
     * @param mixed $value Value of element to be set
     *
     * @return bool If elemnt has been successfuly set
     */
    public function set($identifier, $value);

    /**
     * Returns the concent of all the elements
     *
     * @return array Content of all elements
     */
    public function all();

    /**
     * Remove the content of a string identified element
     *
     * @param string $identifier Element identifier
     *
     * @return mixed value that has been removed
     */
    public function remove($identifier);

    /**
     * Clears all the elements
     *
     * @return All the content that has been cleared
     */
    public function clear();
}
