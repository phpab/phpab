<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Engine;

use PhpAb\Exception\EngineLockedException;
use PhpAb\Exception\TestCollisionException;
use PhpAb\Exception\TestNotFoundException;
use PhpAb\Filter\FilterInterface;
use PhpAb\SubjectInterface;
use PhpAb\Test\Bag;
use PhpAb\Test\TestInterface;
use PhpAb\Chooser\ChooserInterface;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Variant\VariantInterface;

/**
 * The engine used to start tests.
 *
 * @package PhpAb
 */
class Engine implements EngineInterface
{
    /**
     * A list with test bags.
     *
     * @var Bag[]
     */
    public $tests = [];

    /**
     * Locks the engine for further manipulaton
     *
     * @var boolean
     */
    private $locked = false;

    /**
     * {@inheritDoc}
     */
    public function getTests()
    {
        $tests = [];
        foreach ($this->tests as $bag) {
            $tests[] = $bag->getTest();
        }

        return $tests;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $test The identifier of the test
     */
    public function getTest($test)
    {
        if (! isset($this->tests[$test])) {
            throw new TestNotFoundException('No test with identifier '.$test.' found');
        }

        return $this->tests[$test]->getTest();
    }

    /**
     * {@inheritDoc}
     *
     * @param TestInterface $test
     * @param FilterInterface $filter
     * @param ChooserInterface $chooser
     * @param array $options
     */
    public function addTest(
        TestInterface $test,
        FilterInterface $filter,
        ChooserInterface $chooser,
        $options = []
    ) {

        if ($this->locked) {
            throw new EngineLockedException('The engine has been processed already. You cannot add other tests.');
        }

        if (isset($this->tests[$test->getIdentifier()])) {
            throw new TestCollisionException('Duplicate test for identifier '.$test->getIdentifier());
        }

        $this->tests[$test->getIdentifier()] = new Bag($test, $filter, $chooser, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function test(SubjectInterface $subject)
    {
        // Check if already locked
        if ($this->locked) {
            throw new EngineLockedException('The engine is already locked and could not be started once again.');
        }

        // Lock the engine for further manipulation
        $this->locked = true;

        foreach ($this->tests as $testBag) {
            $variant = $this->getChosenVariant(
                $subject,
                $testBag->getTest(),
                $testBag->getParticipationFilter(),
                $testBag->getVariantChooser()
            );
            $variant->run();
        }
    }

    /**
     * Process the test bag
     *
     * @param SubjectInterface $subject
     *
     * @return VariantInterface
     */
    private function getChosenVariant(
        SubjectInterface $subject,
        TestInterface $test,
        FilterInterface $filter,
        ChooserInterface $chooser
    ) {
    
        $dummyVariant = new SimpleVariant(''); // dummy variant to comply with the interface

        if (! $test->getVariants()) {
            // There are no variants for this test
            return $dummyVariant;
        }


        // Check if the user is marked as "do not participate".
        if ($subject->participationIsBlocked($test)) {
            // Events::PARTICIPATION_BLOCKED
            return $dummyVariant;
        }

        // Let's try to recover a previously stored Variant
        if ($rememberedVariantID = $subject->participates($test)) {
            $variant = $test->getVariant($rememberedVariantID);
            return $variant;
        }

        if ($filter->shouldParticipate()) {
            // Choose a variant for later usage. If the user should participate this one will be used
            $chosen = $chooser->chooseVariant($test->getVariants());

            // Store the chosen variant so he will not switch between different states
            $subject->participate($test, $chosen);

            return $chosen;
        }

        // The user should not participate so let's block the participation
        // so he will not participate in the future, too.

        // Events::BLOCK_PARTICIPATION

        $subject->blockParticipationFor($test);
        return $dummyVariant;
    }
}
