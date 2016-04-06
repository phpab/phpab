<?php

namespace PhpAb\Participation;

use PhpAb\Storage\StorageInterface;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;

class Manager implements ParticipationManagerInterface
{
    /**
     * @var \PhpAb\Storage\StorageInterface
     */
    private $storage;

    /**
     * @param \PhpAb\Storage\StorageInterface $storage The storage which should be used
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function participates($test, $variant = null)
    {
        if ($test instanceof TestInterface) {
            $test = $test->getIdentifier();
        }
        
        if ($variant instanceof VariantInterface) {
            $variant = $variant->getIdentifier();
        }

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
     * @inheritDoc
     */
    public function participate($test, $variant)
    {
        if ($test instanceof TestInterface) {
            $test = $test->getIdentifier();
        }

        if ($variant instanceof VariantInterface) {
            $variant = $variant->getIdentifier();
        }

        $this->storage->set($test, $variant);
    }
}
