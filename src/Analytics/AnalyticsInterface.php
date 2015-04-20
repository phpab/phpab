<?php

namespace PhpAb\Analytics;

use PhpAb\AbTest;

/**
 * The interface that should be implemented by all analytic managers.
 */
interface AnalyticsInterface
{
    /**
     * Called when a visitors hits the page where this test is executed.
     *
     * @param AbTest $test The test that is executed.
     */
    public function registerHit(AbTest $test);

    /**
     * Called when a test has already been executed once.
     *
     * @param AbTest $test The test a choice was made for.
     * @param string $choice The choice that was made.
     */
    public function registerExistingVisitor(AbTest $test, $choice);

    /**
     * Called when a test is executed for the first time.
     *
     * @param AbTest $test The test a choice was made for.
     * @param string $choice The choice that was made.
     */
    public function registerNewVisitor(AbTest $test, $choice);
}
