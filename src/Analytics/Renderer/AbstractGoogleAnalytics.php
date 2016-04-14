<?php

namespace PhpAb\Analytics\Renderer;

abstract class AbstractGoogleAnalytics
{

    /**
     * @return string
     */
    abstract public function getScript();

    /**
     * @return array
     */
    abstract public function getParticipations();
}
