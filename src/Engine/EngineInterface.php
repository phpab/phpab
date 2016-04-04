<?php

namespace PhpAb\Engine;

use PhpAb\Exception\TestCollisionException;
use PhpAb\Exception\TestNotFoundException;
use PhpAb\Participation\StorageInterface;
use PhpAb\Test\TestInterface;

interface EngineInterface
{
    /**
     * Gets the storage where information about
     * the users participation is stored.
     *
     * @return StorageInterface
     */
    public function getStorage();

    /**
     * Get the Analytics instance which handles the Events
     * that occur during the test process.
     *
     * This is like a EventListener with limited API
     *
     * @return AnalyticsInterface
     */
    public function getAnalytics();

    /**
     * Get all tests for the engine
     *
     * @return TestInterface[]|array
     */
    public function getTests();

    /**
     * Get a test from the engine
     *
     * @param string $test The identifier of the test
     *
     * @throws TestNotFoundException
     *
     * @return TestInterface
     */
    public function getTest($test);

    /**
     * Adds a test to the Engine
     *
     * @param \PhpAb\Test\TestInterface $test
     * @param array                           $options
     *
     * @throws TestCollisionException
     *
     * @return null
     */
    public function addTest(TestInterface $test, $options = []);

    /**
     * Starts the tests
     *
     * @return null
     */
    public function start();
}
