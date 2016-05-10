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
 * Stores the participation state of the user only for the current request.
 *
 * @package PhpAb
 */
class Runtime implements AdapterInterface
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
     *
     * @param string $identifier The tests identifier
     */
    public function has($identifier)
    {
        return array_key_exists($identifier, $this->data);
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

        return $this->data[$identifier];
    }

    /**
     * {@inheritDoc}
     *
     * @param string $identifier The tests identifier
     * @param mixed  $participation The participated variant
     */
    public function set($identifier, $participation)
    {
        $this->data[$identifier] = $participation;
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
     *
     * @param string $identifier The identifier of the test to remove.
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
