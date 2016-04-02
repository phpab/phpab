<?php

namespace PhpAb\Engine;

use PhpAb\Event\ParticipationEvent;
use PhpAb\Exception\TestCollisionException;
use PhpAb\Exception\TestNotFoundException;
use PhpAb\Participation\StorageInterface;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\SimpleVariant;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpAb\Test\Test;

class Engine implements EngineInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritDoc
     */
    public function getStorage()
    {
        // TODO: Implement getStorage() method.
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
        // TODO: Implement addTest() method.
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        if(0 === rand(0,2)) {
            // dummy test
            // we ran the test already
            $test = new Test('test1');
            $variant = new SimpleVariant('variant1');
            $this->dispatcher->dispatch(
                ParticipationEvent::PARTICIPATION,
                new ParticipationEvent($test, $variant, true)
            );
        }
        
        $test2 = new Test('test2');
        $variant3 = new SimpleVariant('variant3');
        $this->dispatcher->dispatch(
            ParticipationEvent::PARTICIPATION,
            new ParticipationEvent($test2, $variant3, true)
        );
    }
}
