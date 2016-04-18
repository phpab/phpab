<?php

namespace PhpAb\Variant;

/**
 * SimpleVariant does not perform any action on run()
 * It's simple a named Variant
 *
 * It can be used for example for
 * - Control-Group
 * - Simple Frontend-Tests
 */
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
