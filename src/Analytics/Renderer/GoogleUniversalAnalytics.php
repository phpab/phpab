<?php

namespace PhpAb\Analytics\Renderer;

/**
 * This class will only work for Universal Analytics Experiments ran as External
 * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/experiments#pro-server
 */
class GoogleUniversalAnalytics extends AbstractGoogleAnalytics
{

    /**
     * @var array Test identifiers and variation indexes
     */
    private $participations = [];

    /**
     * @param array $participations
     */
    public function __construct(array $participations)
    {
        $this->participations = $participations;
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        if (empty($this->participations)) {
            return '';
        }

        $script = [];

        $script[] = '<script>';

        foreach ($this->participations as $testIdentifier => $variationIndex) {
            $script[] = "ga('set', '" . (string) $testIdentifier . "', " . (int) $variationIndex . ");";
        }

        $script[] = '</script>';

        return implode("\n", $script);
    }
}
