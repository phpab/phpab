<?php

namespace Phpab\Phpab\Test;

use Phpab\Phpab\Participation\FilterInterface;
use Phpab\Phpab\Variant\ChooserInterface;

class Bag
{
    /**
     * @var \Phpab\Phpab\Test\TestInterface
     */
    private $test;

    /**
     * @var array
     */
    private $options;

    /**
     * @var \Phpab\Phpab\Participation\FilterInterface
     */
    private $participationFilter;

    /**
     * @var \Phpab\Phpab\Variant\ChooserInterface
     */
    private $variantChooser;

    /**
     * Bag constructor.
     *
     * @param \Phpab\Phpab\Test\TestInterface            $test The test
     * @param array                                      $options Additional options
     * @param \Phpab\Phpab\Participation\FilterInterface $participationFilter
     * @param \Phpab\Phpab\Variant\ChooserInterface      $variantChooser
     */
    public function __construct(
        TestInterface $test,
        $options = [],
        FilterInterface $participationFilter,
        ChooserInterface $variantChooser
    )
    {
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
