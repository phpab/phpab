<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Engine;

use PhpAb\Exception\TestCollisionException;
use PhpAb\Exception\TestNotFoundException;
use PhpAb\Participation\Filter\FilterInterface;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\Chooser\ChooserInterface;

/**
 * The interface that should be implemented by the engine.
 *
 * @package PhpAb
 */
interface EngineInterface
{
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
     * @throws TestNotFoundException Thrown when the requested test does not exists.
     * @return TestInterface
     */
    public function getTest($test);

    /**
     * Adds a test to the Engine
     *
     * @param TestInterface $test
     * @param array $options
     * @param FilterInterface $filter
     * @param ChooserInterface $chooser
     * @throws TestCollisionException Thrown when the test already exists.
     */
    public function addTest(
        TestInterface $test,
        $options = [],
        FilterInterface $filter = null,
        ChooserInterface $chooser = null
    );

    /**
     * Starts the tests
     *
     * @return null
     */
    public function start();
}
