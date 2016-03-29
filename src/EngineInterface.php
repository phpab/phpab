<?php

namespace Phpab\Phpab;

use Phpab\Phpab\Analytics\AnalyticsInterface;
use Phpab\Phpab\Exception\TestCollisionException;
use Phpab\Phpab\Exception\TestNotFoundException;
use Phpab\Phpab\Storage\StorageInterface;

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
     * @param \Phpab\Phpab\TestInterface $test
     * 
     * @throws TestCollisionException
     */
    public function addTest(TestInterface $test);

    /**
     * Starts the tests
     *
     * @return null
     */
    public function start();
}
