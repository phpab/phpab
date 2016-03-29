<?php

namespace Phpab\Phpab;

interface VariantInterface
{

    /**
     * Gets the Identifier for the variant.
     *
     * This will be stored in storage for participating users.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Run the variant
     *
     * The variant will run if the user is already participaring
     * and has this Variant in the Storage.
     *
     * It will also run if the User has no stored Variant but
     * wins the lottery (or other StrategyInterfaces)
     *
     * TODO: We need params here or in the constructor. E.g. $event
     *
     * @return null
     */
    public function run();

}
