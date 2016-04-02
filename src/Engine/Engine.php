<?php

namespace PhpAb\Engine;

use PhpAb\Exception\EngineLockedException;
use PhpAb\Exception\TestCollisionException;
use PhpAb\Exception\TestNotFoundException;
use PhpAb\Participation\ParticipationManagerInterface;
use PhpAb\Test\TestInterface;

class Engine implements EngineInterface
{
    /**
     * @var \PhpAb\Participation\ParticipationManagerInterface
     */
    private $participationManager;

    /**
     * @var boolean Locks the Engine from adding new tests
     */
    private $locked = false;

    /**
     * Engine constructor.
     *
     * @param \PhpAb\Participation\ParticipationManagerInterface $participationManager
     */
    public function __construct(ParticipationManagerInterface $participationManager)
    {
        $this->participationManager = $participationManager;
    }

    /**
     * @inheritDoc
     */
    public function getTests()
    {
        // TODO: Implement getTests() method.
    }

    /**
     * @inheritDoc
     */
    public function getTest($test)
    {
        // TODO: Implement getTest() method.
    }

    /**
     * @inheritDoc
     */
    public function addTest(TestInterface $test, $options = [])
    {
        if($this->locked) {
            throw new EngineLockedException('You cannot add tests once the engine was started.');
        }

        // TODO: Implement addTest() method.
    }

    /**
     * @inheritDoc
     */
    public function start()
    {
        // Lock the Engine
        $this->locked = true;

        // TODO: Implement start() method.
    }
}
