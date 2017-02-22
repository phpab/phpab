<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link      https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license   https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage;

/**
 * Stores the participation state of the user only for the current request.
 *
 * @package PhpAb
 */
class RuntimeStorage implements StorageInterface
{
    /**
     * @var array The data that has been set.
     */
    private $data;

    /**
     * Initializes a new instance of this class.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function has($identifier)
    {
        return array_key_exists($identifier, $this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function get($identifier)
    {
        if (!$this->has($identifier)) {
            return null;
        }

        return $this->data[$identifier];
    }

    /**
     * {@inheritDoc}
     */
    public function set($identifier, $value)
    {
        $this->data[$identifier] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function clear()
    {
        $removedValues = $this->data;

        $this->data = [];

        return $removedValues;
    }
}
