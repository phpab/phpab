<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2005-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant;

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
        call_user_func($this->callback);
    }
}
