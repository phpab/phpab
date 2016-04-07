<?php

namespace PhpAb\Engine;

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

    public function __construct(ParticipationManagerInterface $participationManager)
    {
        $this->participationManager = $participationManager;
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

            return;
        }

        if (false === $testParticipation) {
            // The user does not participate at the test yet.
            // let him participate
            if (! $bag->getParticipationFilter()->shouldParticipate()) {
                // The user should not participate so let's set participation
                // to null so he will not participate in the future, too.
                $this->participationManager->participate($test->getIdentifier(), null);
                return;
            }

            if (! $test->getVariants()) {
                // There was no variant existent so we will not do anything
                return;
            }

            // He should participate so lets choose an option for him
            $chosen = $bag->getVariantChooser()->chooseVariant($test->getVariants());

            // Store the chosen variant so he will not switch between different states
            $this->participationManager->participate($test->getIdentifier(), $chosen->getIdentifier());

            // Then run the variant
            $chosen->run();

            return;
        }

        // The user has a stored participation
        $variantParticipation = $test->getVariant('bar');
        if (null === $variantParticipation) {
            // oops the stored variant does no longer exist
            // Let the user not apply to the same test a second time
            $this->participationManager->participate($test->getIdentifier(), null);
            return;
        }

        // the variant exists. So GOGOGOGOGOGO.
        $variantParticipation->run();
    }
}
