<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Participation;

use PhpAb\Storage\StorageInterface;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;

/**
 * The participation manager that manages participations for tests.
 *
 * @package PhpAb
 */
class Manager implements ManagerInterface
{
    /**
     * The storage that is used to get participations from.
     *
     * @var StorageInterface
     */
    private $storage;

    /**
     * Initializes a new instance of this class.
     *
     * @param StorageInterface $storage The storage which should be used
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $test The identifier of the test to check.
     * @param string|null $variant The identifier of the variant to check
     */
    public function participates($test, $variant = null)
    {
        $test = $test instanceof TestInterface ? $test->getIdentifier() : $test;
        $variant = $variant instanceof VariantInterface ? $variant->getIdentifier() : $variant;

        $storedValue = $this->storage->get($test);

        if (null !== $variant && $storedValue === $variant) {
            // It was asked explicitly for the variant and it matches
            return true;
        }

        if (null === $variant && $this->storage->has($test)) {
            // The stored value exists, so we participate at the test
            // lets return the stored variant
            return $storedValue;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $test The identifier of the test that should be participated.
     * @param string|false $variant The identifier of the variant that was chosen for the user
     * or false if the user should not participate at the test.
     */
    public function participate($test, $variant)
    {
        $test = $test instanceof TestInterface ? $test->getIdentifier() : $test;
        $variant = $variant instanceof VariantInterface ? $variant->getIdentifier() : $variant;

        $this->storage->set($test, $variant);
    }
}
