<?php

namespace PhpAb\Variant;

class SimpleVariant implements VariantInterface
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @param string   $identifier The Identifier of the Variant
     */
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
    public function run()
    {
        // no return to comply with the interface
    }
}
