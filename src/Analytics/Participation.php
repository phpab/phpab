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
     * @var array
     */
    private $options;

    /**
     * Participation constructor.
     *
     * @param \PhpAb\Test\TestInterface       $test    The test the participation is for
     * @param \PhpAb\Variant\VariantInterface $variant The variant chosen for this test
     * @param array                           $options
     */
    public function __construct(TestInterface $test, VariantInterface $variant, $options = [])
    {
        $this->test = $test;
        $this->variant = $variant;
        $this->options = $options;
    }

    /**
     * @return \PhpAb\Test\TestInterface
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @return \PhpAb\Variant\VariantInterface
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
