<?php

namespace PhpAb\Event;

use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;

class ParticipationEvent
{

    const PARTICIPATION = 'phpab.participation';

    /**
     * @var \PhpAb\Test\TestInterface
     */
    private $test;

    /**
     * @var \PhpAb\Variant\VariantInterface
     */
    private $variant;

    /**
     * @var boolean
     */
    private $isNew;

    public function __construct(TestInterface $test, VariantInterface $variant, $isNew)
    {
        $this->test = $test;
        $this->variant = $variant;
        $this->isNew = $isNew;
    }

    /**
     * @return TestInterface
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @return VariantInterface
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @return boolean
     */
    public function isNew()
    {
        return $this->isNew;
    }
}