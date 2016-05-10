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
 * {@inheritDoc}
 */
class Storage implements StorageInterface
{
    /**
     * @var Adapter\AdapterInterface
     */
    private $adapter;

    /**
     * @param \PhpAb\Storage\Adapter\AdapterInterface $adpterInterface
     */
    public function __construct(Adapter\AdapterInterface $adpterInterface)
    {
        $this->adapter = $adpterInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function has($identifier)
    {
        return $this->adapter->has($identifier);
    }

    /**
     * {@inheritDoc}
     */
    public function get($identifier)
    {
        return $this->adapter->get($identifier);
    }

    /**
     * {@inheritDoc}
     */
    public function set($identifier, $participation)
    {
        $this->adapter->set($identifier, $participation);
    }

    public function clear()
    {
        return $this->adapter->clear();
    }
}
