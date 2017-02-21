<?php

namespace PhpAb\Analytics;

use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;

class Participation
{
    /**
     * @var \PhpAb\Test\TestInterface
     */
    private $test;

    /**
     * @var \PhpAb\Variant\VariantInterface
     */
    private $variant;

    /**
     * Participation constructor.
     *
     * @param \PhpAb\Test\TestInterface $test The test the participation is for
     * @param \PhpAb\Variant\VariantInterface $variant The variant chosen for this test
     */
    public function __construct(TestInterface $test, VariantInterface $variant)
    {
        $this->test = $test;
        $this->variant = $variant;
    }
}