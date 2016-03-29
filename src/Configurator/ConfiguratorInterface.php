<?php

/**
 * @license MIT License
 * @copyright  2016 Phpab Development Team
 */

namespace Phpab\Phpab\Configurator;

/**
 * 
 */
interface ConfiguratorInterface
{

    /**
     * Configures a given Engine instance
     * @param \Phpab\Phpab\Engine $engine
     */
    public function configure(\Phpab\Phpab\Engine $engine);
}
