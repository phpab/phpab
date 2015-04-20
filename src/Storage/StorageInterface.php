<?php

namespace PhpAb\Storage;

use PhpAb\AbTest;

/**
 * The interface that should be implemented by all storage devices.
 */
interface StorageInterface
{
    /**
     * Clears the storage.
     *
     * @param AbTest $abTest The test to clear the storage for.
     */
    public function clear(AbTest $abTest);

    /**
     * Reads the value from the storage.
     *
     * @param AbTest $abTest The test to read the value for.
     * @return string
     */
    public function read(AbTest $abTest);

    /**
     * Writes the value to the storage.
     *
     * @param AbTest $abTest The test to read the value for.
     * @param string $choice The value to write.
     */
    public function write(AbTest $abTest, $choice);
}
