<?php

namespace PhpAb\Engine;

use PhpAb\Event\DispatcherInterface;
use PhpAb\Exception\TestCollisionException;
use PhpAb\Exception\TestNotFoundException;
use PhpAb\Participation\FilterInterface;
use PhpAb\Participation\ParticipationManagerInterface;
use PhpAb\Test\Bag;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\ChooserInterface;

class Engine implements EngineInterface
{
    /**
     * @var Bag[]
     */
    public $tests = [];

    /**
     * @var \PhpAb\Participation\ParticipationManagerInterface
     */
    private $participationManager;
    /**
     * @var \PhpAb\Event\DispatcherInterface
     */
    private $dispatcher;

    public function __construct(ParticipationManagerInterface $participationManager, DispatcherInterface $dispatcher)
    {
        $this->participationManager = $participationManager;
        $this->dispatcher = $dispatcher;
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

        // Choose a variant for later usage.
        // If the user should participate this one will be used
        $chosen = $bag->getVariantChooser()->chooseVariant($test->getVariants());

        if (null === $chosen or !$test->getVariant($chosen->getIdentifier())) {
            // The user has a stored participation, but it does not exist any more
            $this->dispatcher->dispatch('phpab.participation.variant_missing', [$this, $bag]);
            $this->participationManager->participate($test->getIdentifier(), null);

            return false;
        }

        // Store the chosen variant so he will not switch between different states
        $this->participationManager->participate($test->getIdentifier(), $chosen->getIdentifier());

        $this->dispatcher->dispatch('phpab.participation.variant_run', [$chosen]);
        $chosen->run();
    }
}
