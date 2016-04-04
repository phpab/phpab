<?php

namespace PhpAb\Render;

class UAExperiments
{

    /**
     * @var array
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
     * This would be valid for External version of Universal Analytics
     * Anyother implementation won't work (JScript API, redirection Experiments, etc.)
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
