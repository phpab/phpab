<?php

namespace Phpab\Phpab\Analytics;

use Phpab\Phpab\Test;

/**
 * Will return Google Experiments JavaScript 
 * to be included in Page body
 */
class GoogleExperimentsAnalytics extends AbstractJavaScriptAnalytics
{

    public function getScript(array $tests)
    {
        $script = '';

        /* @var $test Test\TestInterface */
        foreach ($tests as $test) {
            // Do the magic
        }

        return $script;
    }

}
