<?php

/**
 * @license MIT License
 * @copyright  2016 Phpab Development Team
 */

namespace Phpab\Phpab;

/**
 * 
 */
class Engine
{

    /**
     *
     * @param \Phpab\Phpab\Storage\StorageInterface $storage
     * @param \Phpab\Phpab\Analytics\AnalyticsInterface $analytics
     * @param \Phpab\Phpab\Configurator\ConfiguratorInterface $configurator
     */
    public function __construct(Storage\StorageInterface $storage, Analytics\AnalyticsInterface $analytics, Configurator\ConfiguratorInterface $configurator = null) {
        // body
    }

    /**
     *
     * @param \Phpab\Phpab\Test $test
     */
    public function addTest(Test $test) {
        // body
    }

}
