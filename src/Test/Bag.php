<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Test;

use PhpAb\Filter\FilterInterface;
use PhpAb\Chooser\ChooserInterface;

/**
 * The combination of a test with options.
 *
 * @package PhpAb
 */
class Bag
{
    /**
     * The test to execute.
     *
     * @var TestInterface
     */
    private $test;

    /**
     * The options for this test.
     *
     * @var array
     */
    private $options;

    /**
     * The participation filter that checks if a guest should participate in the test.
     *
     * @var FilterInterface
     */
    private $participationFilter;

    /**
     * The variant chooser that decides which variant of the test to use.
     *
     * @var ChooserInterface
     */
    private $variantChooser;

    /**
     * Initializes a new instance of this class.
     *
     * @param TestInterface $test The test
     * @param FilterInterface $participationFilter
     * @param ChooserInterface $variantChooser
     * @param array $options Additional options
     */
    public function __construct(
        TestInterface $test,
        FilterInterface $participationFilter,
        ChooserInterface $variantChooser,
        $options = []
    ) {
        $this->test = $test;
        $this->options = $options;
        $this->participationFilter = $participationFilter;
        $this->variantChooser = $variantChooser;
    }

    /**
     * Get the Test from the Bag
     *
     * @return TestInterface
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Get all Options for the Test.
     *
     * Options can be used for data which is not mandatory and
     * can be Implementation specific. Like Google-Experiments ID
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get the Participation Strategy
     *
     * @return FilterInterface
     */
    public function getParticipationFilter()
    {
        return $this->participationFilter;
    }

    /**
     * Get the Variant Chooser.
     *
     * The Variant Chooser chooses the variant after the
     * Strategy allowed the user to participate in the test.
     *
     * @return ChooserInterface
     */
    public function getVariantChooser()
    {
        return $this->variantChooser;
    }
}
