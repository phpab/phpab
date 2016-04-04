<?php

namespace PhpAb\Analytics;

use PhpAb\Test;

/**
 * This is just a concept, no code review required
 */
class UAExperiments
{

    /**
     * @var array
     */
    private $tests = [];

    /**
     * 
     * @param string $testIdentifier
     * @param int $variationIndex
     * @return \PhpAb\Analytics\UAExperiments
     */
    public function addTestResult($testIdentifier, $variationIndex)
    {
        // $testIdentifier is not the name of the test but 
        // the experiment code provided by GAExperiments interface
        // It will look like "Qp0gahJ3RAO3DJ18b0XoUQ"

        // Check for collision?
        $this->tests[$testIdentifier] = $variationIndex;

        return $this;
    }

    /**
     * This would be valid for External version of Universal Analytics
     * Anyother implementation won't work (JScript API, redirection Experiments, etc.)
     * @return string
     */
    public function getScript()
    {
        if (empty($this->tests)) {
            return '';
        }

        $script = [];

        $script[] = '<script>';

        foreach ($this->tests as $testIdentifier => $variationIndex) {
            $script[] = "ga('set', '" . (string) $testIdentifier . "', " . (int) $variationIndex . ");";
        }

        $script[] = '</script>';

        return implode("\n", $script);
    }
}
