<?php

namespace PhpAb;

use PhpAb\Storage\StorageInterface;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;

class Subject implements SubjectInterface
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
     * @param  TestInterface|string         $test    The identifier of the test to check.
     * @param  VariantInterface|string|null $variant The identifier of the variant to check
     * @return boolean|string Returns true when the user participates; false otherwise.
     */
    public function participates(TestInterface $test, VariantInterface $variant = null)
    {
        $testID = $test->getIdentifier();

        if (! $this->storage->has($testID)) {
            return false;
        }

        $storedValue = $this->storage->get($testID);

        // It was asked explicitly for the variant and it matches
        if (null !== $variant) {
            return $storedValue === $variant->getIdentifier();
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param TestInterface|string  $test    The identifier of the test that should be participated.
     * @param VariantInterface|null $variant The identifier of the variant that was chosen or
     * null if the user does not participate in the test.
     */
    public function participate(TestInterface $test, VariantInterface $variant)
    {
        $this->storage->set($test->getIdentifier(), $variant->getIdentifier());
    }

    /**
     * {@inheritDoc}
     */
    public function participationIsBlocked(TestInterface $test)
    {
        // Check if the user is marked as "do not participate".
        return false === $this->storage->get($test->getIdentifier());
    }

    /**
     * {@inheritDoc}
     */
    public function blockParticipationFor(TestInterface $test)
    {
        $this->storage->set($test->getIdentifier(), false);
    }

    public function getVariant(TestInterface $test)
    {
        return $test->getVariant($this->storage->get($test->getIdentifier()));
    }
}
