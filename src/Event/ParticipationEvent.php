<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Event;

use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;

/**
 * Holds information about a participation event which is needed for further processing.
 *
 * @package PhpAb
 */
class ParticipationEvent
{
    /**
     * This Event will be fired once a participation for a user is registered.
     */
    const PARTICIPATION = 'phpab.participation';

    /**
     * The test to participate in.
     *
     * @var TestInterface
     */
    private $test;

    /**
     * The variant that is chosen.
     *
     * @var VariantInterface
     */
    private $variant;

    /**
     * A flag indicating whether or not the user already participates in the test.
     *
     * @var boolean
     */
    private $isNew;

    /**
     * Initializes a new instance of this class.
     * @param TestInterface $test The Test the participation was registered for
     * @param VariantInterface $variant The Variant the user is associated with
     * @param boolean $isNew Indicates weather the user is new or has an old participation from the storage.
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
