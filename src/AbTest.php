<?php

namespace PhpAb;

use InvalidArgumentException;
use PhpAb\Participation\Strategy\StrategyInterface;

/**
 * The definition of a test.
 */
class AbTest implements TestInterface
{
    /**
     * The name of the test.
     *
     * @var string
     */
    private $name;

    /**
     * The callback that should be executed when the A-case is executed.
     *
     * @var callback
     */
    private $callbackA;

    /**
     * The callback that should be executed when the B-case is executed.
     *
     * @var callback
     */
    private $callbackB;

    /**
     * A strategy that decides wheter case A or case B should be executed.
     *
     * @var StrategyInterface
     */
    private $participationStrategy;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $name The name of the test case.
     * @param callable $callbackA The A-case callback.
     * @param callable $callbackB The B-case callback.
     * @param StrategyInterface $participationStrategy The strategy that decides the case to execute.
     */
    public function __construct($name, $callbackA, $callbackB, StrategyInterface $participationStrategy = null)
    {
        if (!is_callable($callbackA)) {
            throw new InvalidArgumentException('Callback A is not callable.');
        }

        if (!is_callable($callbackB)) {
            throw new InvalidArgumentException('Callback B is not callable.');
        }

        $this->name = $name;
        $this->callbackA = $callbackA;
        $this->callbackB = $callbackB;
        $this->participationStrategy = $participationStrategy;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getCallback($identifier)
    {
        if('A' === $identifier) {
            return $this->callbackA;
        }

        if('B' === $identifier) {
            return $this->callbackB;
        }

        throw new InvalidArgumentException("The Identifier must be one of [A,B]. Given: \"$identifier\"");
    }


    /**
     * Gets the A-case callback of this test.
     * @deprecated since 1.0.0 in favor of getCallback
     *
     * @return callable
     */
    public function getCallbackA()
    {
        return $this->callbackA;
    }

    /**
     * Gets the B-case callback of this test.
     * @deprecated since 1.0.0 in favor of getCallback
     *
     * @return callable
     */
    public function getCallbackB()
    {
        return $this->callbackB;
    }

    /**
     * @inheritDoc
     */
    public function getParticipationStrategy()
    {
        return $this->participationStrategy;
    }
}
