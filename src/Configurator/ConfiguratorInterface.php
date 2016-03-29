<?php

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
