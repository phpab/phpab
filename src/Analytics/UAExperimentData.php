<?php

namespace PhpAb\Analytics;

use PhpAb\Test;

/**
 * This is just a concept, no code review required
 */
class UAExperimentData
{

    /**
     * @var array
     */
    private $testsData = [];

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
        $this->testsData[$testIdentifier] = $variationIndex;

        return $this;
    }

    /**
     * @return array
     */
    public function getTestsData()
    {
        return $this->testsData;
    }
}
