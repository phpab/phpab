<?php

namespace PhpAb\Analytics;

use Webmozart\Assert\Assert;

class UAExperimentData
{

    /**
     * @var array Of test identifiers and their variant's indexes
     */
    private $testsData = [];

    /**
     * 
     * @param string $testIdentifier It will look like "Qp0gahJ3RAO3DJ18b0XoUQ"
     * @param int $variationIndex
     * @return \PhpAb\Analytics\UAExperiments
     * @throws InvalidArgumentException
     */
    public function onRegisterParticipation($testIdentifier, $variationIndex)
    {
        Assert::string($testIdentifier, 'Test identifier must be astring');

        Assert::integer($variationIndex, 'Variation index must be integer');

        Assert::greaterThan($variationIndex, -1, 'Variation index must be integer >= 0');

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
