<?php

namespace PhpAb\Storage;

use PhpAb\TestInterface;

/**
 * The SessionStorage class holds the value of the test in a session meaning that the test will only be active
 * for the current session.
 */
class SessionStorage implements StorageInterface
{
    /**
     * The name of the session.
     *
     * @var string
     */
    private $name;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $name The name of the session.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Builds the name of the session for the given test.
     *
     * @param TestInterface $test The test to build the name of the session for.
     * @return string
     */
    private function getSessionName(TestInterface $test)
    {
        $replaced = preg_replace('/[^a-z0-9]+/i', '_', $this->name . '_' . $test->getName());

        return strtolower($replaced);
    }

    /**
     * @inheritdoc
     */
    public function clear(TestInterface $test)
    {
        $sessionName = $this->getSessionName($test);

        unset($_SESSION[$sessionName]);
    }

    /**
     * @inheritdoc
     */
    public function read(TestInterface $test)
    {
        $sessionName = $this->getSessionName($test);

        return isset($_SESSION[$sessionName]) ? $_SESSION[$sessionName] : null;
    }

    /**
     * @inheritdoc
     */
    public function write(TestInterface $test, $choice)
    {
        $sessionName = $this->getSessionName($test);

        $_SESSION[$sessionName] = $choice;
    }
}
