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
     * Gets the variant the user is participating in for the given test.
     *
     * @param TestInterface|string $test The identifier of the test to get the variant for.
     * @return string|null Returns the identifier of the variant or null if not participating.
     */
    public function getParticipatingVariant($test)
    {
        $test = $test instanceof TestInterface ? $test->getIdentifier() : $test;

        if ($this->storage->has($test)) {
            return $this->storage->get($test);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @param TestInterface|string $test The identifier of the test to check.
     * @param VariantInterface|string|null $variant The identifier of the variant to check
     * @return boolean|string Returns true when the user participates; false otherwise.
     */
    public function participates($test, $variant = null)
    {
        $test = $test instanceof TestInterface ? $test->getIdentifier() : $test;
        $variant = $variant instanceof VariantInterface ? $variant->getIdentifier() : $variant;

        if (!$this->storage->has($test)) {
            return false;
        }

        $storedValue = $this->storage->get($test);

        // It was asked explicitly for the variant and it matches
        if (null !== $variant) {
            return $storedValue === $variant;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param TestInterface|string $test The identifier of the test that should be participated.
     * @param VariantInterface|string|null $variant The identifier of the variant that was chosen or
     * null if the user does not participate in the test.
     */
    public function participate($test, $variant)
    {
        $test = $test instanceof TestInterface ? $test->getIdentifier() : $test;
        $variant = $variant instanceof VariantInterface ? $variant->getIdentifier() : $variant;

        $this->storage->set($test, $variant);
    }
}
