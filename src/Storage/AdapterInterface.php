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
 * Used by StorageInterface to store user participations
 *
 * @package PhpAb
 */
interface AdapterInterface
{

    public function has($identifier);

    public function get($identifier);

    public function set($identifier, $participation);

    public function all();

    public function remove($identifier);

    public function clear();
}
