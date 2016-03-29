<?php

namespace Phpab\Phpab;

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
     * The Variant will run if the user is already participating
     * and has this Variant in the Storage.
     *
     * It will also run if the User has no stored Variant but
     *
     * @return null
     */
    public function run();

}
