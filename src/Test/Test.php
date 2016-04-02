<?php

namespace PhpAb\Test;

use PhpAb\Variant\VariantInterface;

class Test implements TestInterface
{
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function getVariants()
    {
        // TODO: Implement getVariants() method.
    }

    /**
     * @inheritDoc
     */
    public function getVariant($variant)
    {
        // TODO: Implement getVariant() method.
    }

}
