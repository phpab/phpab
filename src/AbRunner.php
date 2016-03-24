<?php

namespace PhpAb;

use PhpAb\Analytics\AnalyticsInterface;
use PhpAb\Participation\Strategy\StrategyInterface;
use PhpAb\Storage\StorageInterface;
use RuntimeException;

class AbRunner
{
    const CHOICE_NONE = null;
    const CHOICE_A = 'A';
    const CHOICE_B = 'B';

    /**
     * A list with all tests that should be executed.
     *
     * @var AbTest[]
     */
    private $tests;

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
        $this->tests = array();
        $this->participationStrategy = $participationStrategy;
    }

    /**
     * Adds a test to the runner.
     *
     * @param AbTest $test The test to add.
     */
    public function addTest(AbTest $test)
    {
        $this->tests[] = $test;
    }

    /**
     * Gets all the tests that are executed.
     *
     * @return AbTest[]
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * Sets the tests that should be executed.
     *
     * @param AbTest[] $tests The tests to execute.
     */
    public function setTests($tests)
    {
        $this->tests = array();

        foreach ($tests as $test) {
            $this->addTest($test);
        }
    }

    /**
     * Gets the analytics service used to register statistics about the tests.
     *
     * @return AnalyticsInterface
     */
    public function getAnalytics()
    {
        return $this->analytics;
    }

    /**
     * Sets the analytics service used to register statistics about the tests.
     *
     * @param AnalyticsInterface $analytics The analytics service to set.
     */
    public function setAnalytics(AnalyticsInterface $analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * Gets the storage container that is used to identify users.
     *
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Sets the storage container that is used to identify users.
     *
     * @param StorageInterface $storage The storage container to set.
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Gets the participation strategy.
     *
     * @return StrategyInterface
     */
    public function getParticipationStrategy()
    {
        return $this->participationStrategy;
    }

    /**
     * Executes the tests.
     *
     * @return int Returns the amount of executed tests.
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
     * @param AbTest $test The test to execute.
     * @return bool Returns true when the test is executed; false otherwise.
     */
    private function executeTest(AbTest $test)
    {
        if ($this->getStorage()) {
            $choice = $this->getStorage()->read($test);
            if ($choice !== self::CHOICE_NONE) {
                $this->executeChoice($test, $choice, false);
                return true;
            }
        }

        if ($this->getParticipationStrategy() &&
            !$this->getParticipationStrategy()->isParticipating($this)) {
            return false;
        }

        if ($test->getParticipationStrategy()) {
            $isParticipating = $test->getParticipationStrategy()->isParticipating($this);
        } else {
            $isParticipating = true;
        }
        
        if ($isParticipating) {
            $this->executeChoice($test, self::CHOICE_B, true);
        } else {
            $this->executeChoice($test, self::CHOICE_A, true);
        }

        return true;
    }

    /**
     * Executes the choice that is made.
     *
     * @param AbTest $test The test a choice is made for.
     * @param string $choice The choice that is made.
     * @param bool $firstTime Whether or not this was the first time the test was executed.
     * @throws RuntimeException Thrown when no valid choice is provided.
     */
    private function executeChoice(AbTest $test, $choice, $firstTime)
    {
        if ($firstTime && $this->getStorage()) {
            $this->getStorage()->write($test, $choice);
        }

        if ($this->getAnalytics() && $firstTime) {
            $this->getAnalytics()->registerNewVisitor($test, $choice);
        } elseif ($this->getAnalytics() && !$firstTime) {
            $this->getAnalytics()->registerExistingVisitor($test, $choice);
        }

        switch ($choice) {
            case self::CHOICE_A:
                call_user_func_array($test->getCallback(self::CHOICE_A), array($this, $test, $choice));
                break;

            case self::CHOICE_B:
                call_user_func_array($test->getCallback(self::CHOICE_B), array($this, $test, $choice));
                break;

            case self::CHOICE_NONE:
            default:
                throw new RuntimeException('The choice "' . $choice . '" is not implemented.');
        }
    }
}
