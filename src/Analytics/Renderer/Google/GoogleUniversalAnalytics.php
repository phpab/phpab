<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\Renderer\Google;

/**
 * This class will only work for Universal Analytics Experiments ran as External
 *
 * @package PhpAb
 * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/experiments#pro-server
 */
class GoogleUniversalAnalytics extends AbstractGoogleAnalytics
{
    /**
     * The map with test identifiers and variation indexes.
     *
     * @var array
     */
    private $participations = [];

    /**
     * Initializes a new instance of this class.
     *
     * @param array $participations
     */
    public function __construct(array $participations)
    {
        $this->participations = $participations;
    }

    /**
     * {@inheritDoc}
     */
    public function getScript()
    {
        if (empty($this->participations)) {
            return '';
        }

        $script = [];

        if (true === $this->getApiCLientInclusion()) {
            $script[] = '<script src="//www.google-analytics.com/cx/api.js"></script>';
        }

        $script[] = '<script>';

        foreach ($this->participations as $testIdentifier => $variationIndex) {
            $script[] = "ga('set', '" . (string) $testIdentifier . "', " . (int) $variationIndex . ");";
        }

        $script[] = '</script>';

        return implode(PHP_EOL, $script);
    }

    /**
     * {@inheritDoc}
     */
    public function getParticipations()
    {

        return $this->participations;
    }
}
