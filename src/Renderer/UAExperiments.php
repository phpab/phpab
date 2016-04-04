<?php

namespace PhpAb\Renderer;

/**
 * This class will only work for Universal Analytics
 * Experiments ran as External
 */
class UAExperiments
{

    /**
     * @var array Of test identifiers and their variant's indexes
     */
    private $testsData = [];

    /**
     * @param array $testsData
     */
    public function __construct(array $testsData)
    {
        $this->testsData = $testsData;
    }

    /**
     * @return string
     */
    public function getScript()
    {
        if (empty($this->testsData)) {
            return '';
        }

        $script = [];

        $script[] = '<script>';

        foreach ($this->testsData as $testIdentifier => $variationIndex) {
            $script[] = "ga('set', '" . (string) $testIdentifier . "', " . (int) $variationIndex . ");";
        }

        $script[] = '</script>';

        return implode("\n", $script);
    }
}
