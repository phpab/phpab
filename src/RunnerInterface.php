<?php

namespace PhpAb;

use PhpAb\Analytics\AnalyticsInterface;
use PhpAb\Storage\StorageInterface;
use PhpAb\Participation\Strategy\StrategyInterface;

interface RunnerInterface
{

    /**
     * Adds a test to the runner.
     *
     * @param TestInterface $test The test to add.
     */
    public function addTest(TestInterface $test);

    /**
     * Gets all the tests that are executed.
     *
     * @return TestInterface[]
     */
    public function getTests();

    /**
     * Sets the tests that should be executed.
     *
     * @param TestInterface[] $tests The tests to execute.
     */
    public function setTests($tests);

    /**
     * Gets the analytics service used to register statistics about the tests.
     *
     * @return AnalyticsInterface
     */
    public function getAnalytics();

    /**
     * Sets the analytics service used to register statistics about the tests.
     *
     * @param AnalyticsInterface $analytics The analytics service to set.
     */
    public function setAnalytics(AnalyticsInterface $analytics);

    /**
     * Gets the storage container that is used to identify users.
     *
     * @return StorageInterface
     */
    public function getStorage();

    /**
     * Sets the storage container that is used to identify users.
     *
     * @param StorageInterface $storage The storage container to set.
     */
    public function setStorage(StorageInterface $storage);

    /**
     * Gets the participation strategy.
     *
     * @return StrategyInterface
     */
    public function getParticipationStrategy();

    /**
     * Executes the tests.
     *
     * @return int Returns the amount of executed tests.
     */
    public function test();
}
