<?php

/**
 * @license MIT License
 * @copyright  2016 Phpab Development Team
 */

namespace Phpab\Phpab;

/**
 *
 */
class Engine
{

    /**
     *
     * @var array
     */
    protected $tests = [];

    /**
     *
     * @var Storage\StorageInterface
     */
    protected $storage;

    /**
     *
     * @var Analytics\AnalyticsInterface
     */
    protected $analytics;

    /**
     *
     * @param \Phpab\Phpab\Storage\StorageInterface $storage
     * @param \Phpab\Phpab\Analytics\AnalyticsInterface $analytics
     */
    public function __construct(Storage\StorageInterface $storage, Analytics\AnalyticsInterface $analytics) {

        $this->storage = $storage;

        $this->analytics = $analytics;

    }

    /**
     *
     * @param \Phpab\Phpab\Test $test
     */
    public function addTest(Test $test) {

        //@todo: TBD: This should avoid running the same test twice and have unexpected results
        $this->tests[$test->getName()] = $test;

    }

    /**
     *
     * @param string $test_name
     * @return Test
     */
    public function getTest($test_name) {

        if (isset($this->tests[$test_name])) {

            return $this->tests[$test_name];
        }

        // @todo: TBD: return null or throw TestNotFoundException?
        return null;
    }
}
