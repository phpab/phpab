<?php

namespace PhpAb;

use PhpAb\Analytics\AnalyticsInterface;
use PhpAb\Participation\Strategy\StrategyInterface;
use PhpAb\Storage\StorageInterface;
use RuntimeException;

/**
 * Class Runner
 *
 * @package \PhpAb
 */
abstract class Runner implements RunnerInterface
{
    const CHOICE_NONE = null;
    const CHOICE_A = 'A';
    const CHOICE_B = 'B';

    /**
     * A list with all tests that should be executed.
     *
     * @var TestInterface[]
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
        if ($choice = $this->getStoredChoice($test)) {
            $this->executeChoice($test, $choice, false);
            return true;
        }

        if ($this->getParticipationStrategy() &&
            !$this->getParticipationStrategy()->isParticipating($this)) {
            return false;
        }

        if ($this->isParticipating($test)) {
            $this->executeChoice($test, self::CHOICE_B, true);
        } else {
            $this->executeChoice($test, self::CHOICE_A, true);
        }

        return true;
    }

    /**
     * Checks if the visitor is participating in the test
     *
     * @param \PhpAb\TestInterface $test
     * @return bool
     */
    private function isParticipating(TestInterface $test)
    {
        if(! $test->getParticipationStrategy()) {
            // There was no participation strategy given
            // Let the visitor always participate
            return true;
        }

        return $test->getParticipationStrategy()->isParticipating($this);
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
