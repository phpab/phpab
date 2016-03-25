<?php

namespace PhpAb\Storage;

use PhpAb\TestInterface;

/**
 * The interface that should be implemented by all storage devices.
 */
interface StorageInterface
{
    /**
     * Clears the storage.
     *
     * @param TestInterface $abTest The test to clear the storage for.
     */
    public function clear(TestInterface $abTest);

    /**
     * Reads the value from the storage.
     *
     * @param TestInterface $abTest The test to read the value for.
     * @return string|null The value of the choice
     */
    public function read(TestInterface $abTest);

    /**
     * Writes the value to the storage.
     *
     * @param TestInterface $abTest The test to read the value for.
     * @param string $choice The value to write.
     */
    public function write(TestInterface $abTest, $choice);
}
