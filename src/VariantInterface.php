<?php

namespace Phpab\Phpab;

use Phpab\Phpab\Exception\TestExecutionException;

interface VariantInterface
{
    /**
     * Gets the Identifier for the Variant.
     *
     * This will be stored in storage for participating users.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Run the Variant
     *
     * @throws TestExecutionException
     *
     * @return null
     */
    public function run();
}
