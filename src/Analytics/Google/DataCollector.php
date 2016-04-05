<?php

namespace PhpAb\Analytics\Google;

use Webmozart\Assert\Assert;

class DataCollector
{

    /**
     * @var array Test identifiers and variation indexes
     */
    private $participations = [];

    /**
     * @param string $testIdentifier It will look like "Qp0gahJ3RAO3DJ18b0XoUQ"
     * @param int $variationIndex
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function onRegisterParticipation($testIdentifier, $variationIndex)
    {
        Assert::string($testIdentifier, 'Test identifier must be a string');

        Assert::integer($variationIndex, 'Variation index must be integer');

        Assert::greaterThan($variationIndex, -1, 'Variation index must be integer >= 0');

        $this->participations[$testIdentifier] = $variationIndex;
    }

    /**
     * @return array
     */
    public function getTestsData()
    {
        return $this->participations;
    }
}
