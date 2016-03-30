<?php

namespace Phpab\Phpab;

class CallbackVariant implements VariantInterface
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @param string   $identifier The Identifier of the Variant
     * @param callable $callback The Callable to execute on run
     */
    public function __construct($identifier, callable $callback)
    {
        $this->identifier = $identifier;
        $this->callback = $callback;
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
        return call_user_func($this->callback);
    }
}
