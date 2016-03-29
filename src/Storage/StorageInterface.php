<?php

/**
 * @license MIT License
 * @copyright  2016 Phpab Development Team
 */

namespace Phpab\Phpab\Storage;

/**
 * 
 */
interface StorageInterface
{

    /**
     * Clears the storage.
     *
     * @param TestInterface $test The test to clear the storage for.
     */
    public function clear(TestInterface $test);
    /**
     * Reads the value from the storage.
     *
     * @param TestInterface $test The test to read the value for.
     *
     * @return string|null The value of the choice
     */
    public function read(TestInterface $test);
    /**
     * Writes the value to the storage.
     *
     * @param TestInterface $test The test to read the value for.
     *
     * @param string $choice The value to write.
     */
    public function write(TestInterface $test, $choice);

}
