<?php

namespace PhpAb\Test;

use PhpAb\Participation\FilterInterface;
use PhpAb\Variant\ChooserInterface;

class Bag
{
    /**
     * @var \PhpAb\Test\TestInterface
     */
    private $test;

    /**
     * @var array
     */
    private $options;

    /**
     * @var \PhpAb\Participation\FilterInterface
     */
    private $participationFilter;

    /**
     * @var \PhpAb\Variant\ChooserInterface
     */
    private $variantChooser;

    /**
     * Bag constructor.
     *
     * @param \PhpAb\Test\TestInterface            $test The test
     * @param \PhpAb\Participation\FilterInterface $participationFilter
     * @param \PhpAb\Variant\ChooserInterface      $variantChooser
     * @param array                                      $options Additional options
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
