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
 * This class will only work for Classic Analytics Experiments ran as External
 *
 * @package PhpAb
 * @see https://developers.google.com/analytics/devguides/collection/gajs/experiments#cxjs-setchosen
 */
class GoogleClassicAnalytics extends AbstractGoogleAnalytics
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

        $script[] = '<script>';

        foreach ($this->participations as $testIdentifier => $variationIndex) {
            $script[] = "cxApi.setChosenVariation(" . (int) $variationIndex . ", '" . (string) $testIdentifier . "')";
        }

        $script[] = '</script>';

        return implode("\n", $script);
    }

    /**
     * {@inheritDoc}
     */
    public function getParticipations()
    {

        return $this->participations;
    }
}
