<?php

namespace PhpAb\Analytics;

use PhpAb\TestInterface;

/**
 * The interface that should be implemented by all analytic managers.
 */
interface AnalyticsInterface
{
    /**
     * Called when a visitors hits the page where this test is executed.
     *
     * @param TestInterface $test The test that is executed.
     */
    public function registerHit(TestInterface $test);

    /**
     * Called when a test has already been executed once.
     *
     * @param TestInterface $test The test a choice was made for.
     * @param string $choice The choice that was made.
     */
    public function registerExistingVisitor(TestInterface $test, $choice);

    /**
     * Called when a test is executed for the first time.
     *
     * @param TestInterface $test The test a choice was made for.
     * @param string $choice The choice that was made.
     */
    public function registerNewVisitor(TestInterface $test, $choice);
}
