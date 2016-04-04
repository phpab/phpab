<?php

namespace PhpAb\Event;

use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;

/**
 * Holds information about a participation event which is needed for
 * further processing.
 */
class ParticipationEvent
{
    /**
     * This Event will be fired once a participation
     * for a user is registered.
     */
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

    /**
     * @param \PhpAb\Test\TestInterface       $test The Test the participation was registered for
     * @param \PhpAb\Variant\VariantInterface $variant The Variant the user is associated with
     * @param boolean                         $isNew  Indicates weather the user is new or has an old participation
     *                                                from the storage.
     */
    public function __construct(TestInterface $test, VariantInterface $variant, $isNew)
    {
        $this->test = $test;
        $this->variant = $variant;
        $this->isNew = $isNew;
    }

    /**
     * Get the Test the participation was registered for
     *
     * @return TestInterface
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Get the Variant the user is associated with
     *
     * @return VariantInterface
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * Checks weather the participation of the user is new
     *
     * @return boolean
     */
    public function isNew()
    {
        return $this->isNew;
    }
}
