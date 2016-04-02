<?php

namespace Phpab\Phpab\Analytics;

/**
 * Abstract class implemented by Analytics based on JavaScript
 */
abstract class AbstractJavaScriptAnalytics implements AnalyticsInterface
{

    /**
     * Returns the script required for registering tests 
     * variants via JavaScript files
     * @param array $tests Array of TestInterface
     */
    abstract public function getScript(array $tests);
}
