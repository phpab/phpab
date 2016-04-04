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
     * @var string
     */
    private $prefix;

    /**
     * @param \PhpAb\Storage\StorageInterface $storage The storage which should be used
     * @param string                          $prefix  The prefix to avoid collision with other stored variables
     */
    public function __construct(StorageInterface $storage, $prefix = 'phpab_')
    {
        $this->storage = $storage;
        $this->prefix = $prefix;
    }

    /**
     * @inheritDoc
     */
    public function participates($test, $variant = null)
    {
        $test = $this->getIdentifier($test);
        $variant = $this->getIdentifier($variant);

        $storedValue = $this->storage->get($this->getKey($test));

        if (null !== $variant && $storedValue === $variant) {
            // Is wasn't asked expilcitly for the variant, so we will only make
            // a check for the test.
            return true;
        }

        if (null === $variant && $this->storage->has($this->getKey($test))) {
            // The stored value exists, so we participate at the test
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function participate($test, $variant)
    {
        $test = $this->getIdentifier($test);
        $variant = $this->getIdentifier($variant);

        $this->storage->set($this->getKey($test), $variant);
    }

    /**
     * @param string $identifier The indentifier to build the key with
     *
     * @return string The namespaced key
     */
    private function getKey($identifier)
    {
        return $this->prefix.$identifier;
    }

    /**
     * @param mixed $subject The subject to get the identifier for
     *
     * @return string The identifier of the item
     */
    private function getIdentifier($subject)
    {
        if ($subject instanceof TestInterface) {
            return $subject->getIdentifier();
        }

        if ($subject instanceof VariantInterface) {
            return $subject->getIdentifier();
        }

        return $subject;
    }
}
