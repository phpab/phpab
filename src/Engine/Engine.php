<?php

namespace PhpAb\Engine;

use PhpAb\Event\DispatcherInterface;
use PhpAb\Exception\TestCollisionException;
use PhpAb\Exception\TestNotFoundException;
use PhpAb\Participation\FilterInterface;
use PhpAb\Participation\ParticipationManagerInterface;
use PhpAb\Test\Bag;
use PhpAb\Variant;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\ChooserInterface;

class Engine implements EngineInterface
{
    /**
     * @var Bag[]
     */
    public $tests = [];

    /**
     * @var ParticipationManagerInterface
     */
    private $participationManager;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;

    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * @var ChooserInterface
     */
    private $chooser;

    /**
     * @param ParticipationManagerInterface $participationManager Handles the Participation state
     * @param DispatcherInterface $dispatcher Dispatches events
     * @param FilterInterface|null $filter The default filter to use if no filter is provided
     *                                     for the test.
     * @param ChooserInterface|null $chooser The default chooser to use if no chooser is provided
     *                                       for the test.
     */
    public function __construct(
        ParticipationManagerInterface $participationManager,
        DispatcherInterface $dispatcher,
        FilterInterface $filter = null,
        ChooserInterface $chooser = null
    ) {

        $this->participationManager = $participationManager;
        $this->dispatcher = $dispatcher;
        $this->filter = $filter;
        $this->chooser = $chooser;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getTest($test)
    {
        if (! isset($this->tests[$test])) {
            throw new TestNotFoundException('No test with identifier '.$test.' found');
        }

        return $this->tests[$test]->getTest();
    }

    /**
     * @inheritDoc
     */
    public function addTest(
        TestInterface $test,
        $options = [],
        FilterInterface $filter = null,
        ChooserInterface $chooser = null
    ) {

        if (isset($this->tests[$test->getIdentifier()])) {
            throw new TestCollisionException('Duplicate test for identifier '.$test->getIdentifier());
        }

        // If no filter/chooser is set use the ones from
        // the engine.
        $filter = $filter ? $filter : $this->filter;
        $chooser = $chooser ? $chooser : $this->chooser;

        $this->tests[$test->getIdentifier()] = new Bag($test, $filter, $chooser, $options);
    }

    /**
     * @inheritDoc
     */
    public function start()
    {
        foreach ($this->tests as $testBag) {
            $this->handleTestBag($testBag);
        }
    }

    /**
     * Process the test bag
     *
     * @param Bag $bag
     *
     * @return bool true if the variant got executed, false otherwise
     */
    private function handleTestBag(Bag $bag)
    {
        $test = $bag->getTest();
        $testParticipation = $this->participationManager->participates($test->getIdentifier());

        if (null === $testParticipation) {
            // is marked as "do not participate"
            $this->dispatcher->dispatch('phpab.participation.blocked', [$this, $bag]);

            return false;
        }

        if (false === $testParticipation) {
            // The user does not participate at the test
            // let him participate
            if (! $bag->getParticipationFilter()->shouldParticipate()) {
                // The user should not participate so let's set participation
                // to null so he will not participate in the future, too.
                $this->dispatcher->dispatch('phpab.participation.block', [$this, $bag]);

                $this->participationManager->participate($test->getIdentifier(), null);

                return false;
            }
        }

        // Let's try to recover a previously stored Variant
        if ($testParticipation) {
            $variant = $bag->getTest()->getVariant($testParticipation);
            // If we managed to identifier a Variant by a previously stored
            // participation, do its magic again
            if ($variant instanceof Variant\VariantInterface) {
                $this->dispatcher->dispatch('phpab.participation.variant_run', [$this, $bag, $variant]);
                $variant->run();

                return true;
            }
        }

        // Choose a variant for later usage.
        // If the user should participate this one will be used
        $chosen = $bag->getVariantChooser()->chooseVariant($test->getVariants());

        if (null === $chosen || !$test->getVariant($chosen->getIdentifier())) {
            // The user has a stored participation, but it does not exist any more
            $this->dispatcher->dispatch('phpab.participation.variant_missing', [$this, $bag]);
            $this->participationManager->participate($test->getIdentifier(), null);

            return false;
        }

        // Store the chosen variant so he will not switch between different states
        $this->participationManager->participate($test->getIdentifier(), $chosen->getIdentifier());

        $this->dispatcher->dispatch('phpab.participation.variant_run', [$this, $bag, $chosen]);
        $chosen->run();

        return true;
    }
}
