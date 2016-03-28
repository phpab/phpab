<?php

namespace PhpAb;

use PhpAb\Analytics\AnalyticsInterface;
use PhpAb\Participation\Strategy\StrategyInterface;
use PhpAb\Storage\StorageInterface;
use RuntimeException;

class AbRunner implements RunnerInterface
{
    const CHOICE_NONE = null;
    const CHOICE_A = 'A';
    const CHOICE_B = 'B';

    /**
     * A list with all tests that should be executed.
     *
     * @var TestInterface[]
     */
    private $tests = array();

    /**
     * @var AnalyticsInterface
     */
    private $analytics;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var StrategyInterface
     */
    private $participationStrategy;

    /**
     * Initializes a new instance of this class.
     *
     * @param StrategyInterface $participationStrategy The strategy used to decide if a visitor is part of the tests.
     */
    public function __construct(StrategyInterface $participationStrategy = null)
    {
        $this->participationStrategy = $participationStrategy;
    }

    /**
     * @inheritDoc
     */
    public function addTest(TestInterface $test)
    {
        $this->tests[] = $test;
    }

    /**
     * @inheritDoc
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * @inheritDoc
     */
    public function setTests($tests)
    {
        $this->tests = array();

        foreach ($tests as $test) {
            $this->addTest($test);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAnalytics()
    {
        return $this->analytics;
    }

    /**
     * @inheritDoc
     */
    public function setAnalytics(AnalyticsInterface $analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * @inheritDoc
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @inheritDoc
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function getParticipationStrategy()
    {
        return $this->participationStrategy;
    }

    /**
     * @inheritDoc
     */
    public function test()
    {
        $executedTests = 0;

        foreach ($this->getTests() as $test) {
            if ($this->executeTest($test)) {
                $executedTests++;
            }
        }

        return $executedTests;
    }

    /**
     * Executes the provided test.
     *
     * @param TestInterface $test The test to execute.
     * @return bool Returns true when the test is executed; false otherwise.
     */
    private function executeTest(TestInterface $test)
    {
        // Take the strategy from the test if it's set
        // If not, take the strategy from the runner
        $strategy = $test->getParticipationStrategy()
            ? $test->getParticipationStrategy()
            : $this->getParticipationStrategy();

        if (null === $strategy) {
            // There was no strategy set.
            // Not in the test nor in the runner.
            // So we pretend that the test should always run

            $this->executeChoice($test, $test->choose(), true);
            return true;
        }

        if (! $strategy->isParticipating($this)) {
            return false;
        }

        if ($choice = $this->getStoredChoice($test)) {
            // execute the choice which was stored for the visitor
            $this->executeChoice($test, $choice, false);
            return true;
        }

        $this->executeChoice($test, $test->choose(), true);
        return true;
    }

    /**
     * Get the choice from storage
     *
     * @param \PhpAb\TestInterface $test
     * @return null|string the value of the choice or null
     */
    private function getStoredChoice(TestInterface $test)
    {
        if (! $this->getStorage()) {
            return null;
        }

        return $this->getStorage()->read($test);
    }

    /**
     * Executes the choice that is made.
     *
     * @param TestInterface $test The test a choice is made for.
     * @param string $choice The choice that is made.
     * @param bool $firstTime Whether or not this was the first time the test was executed.
     * @throws RuntimeException Thrown when no valid choice is provided.
     */
    private function executeChoice(TestInterface $test, $choice, $firstTime)
    {
        if ($firstTime && $this->getStorage()) {
            $this->getStorage()->write($test, $choice);
        }

        if ($this->getAnalytics() && $firstTime) {
            $this->getAnalytics()->registerNewVisitor($test, $choice);
        } elseif ($this->getAnalytics() && !$firstTime) {
            $this->getAnalytics()->registerExistingVisitor($test, $choice);
        }

        call_user_func_array($test->getCallback($choice), array($this, $test, $choice));
    }
}
