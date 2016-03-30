<?php

namespace Phpab\Phpab\Test;

use Phpab\Phpab\Participation\FilterInterface;
use Phpab\Phpab\Variant\ChooserInterface;

interface TestInterface
{

    /**
     * Get the identifier for this test
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get all variants for this test
     *
     * @return VariantInterface[]
     */
    public function getVariants();

    /**
     * Get a single variant for this test
     *
     * @param string $variant The variants Identifier
     *
     * @return mixed
     */
    public function getVariant($variant);

    /**
     * Get all Options for the Test.
     *
     * Options can be used for data which is not mandatory and
     * can be Implementation specific. Like Google-Experiments ID
     *
     * @return array
     */
    public function getOptions();

    /**
     * Get the Participation Strategy
     *
     * @return FilterInterface
     */
    public function getParticipationFilter();

    /**
     * Get the Variant Chooser.
     *
     * The Variant Chooser chooses the variant after the
     * Strategy allowed the user to participate in the test.
     *
     * @return ChooserInterface
     */
    public function getVariantChooser();
}
