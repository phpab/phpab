<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link      https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license   https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant;

use PhpAb\Exception\TestExecutionException;

/**
 * The interface that should be implemented by all variants.
 *
 * @package PhpAb
 */
interface VariantInterface
{
    /**
     * Gets the Identifier for the variant.
     * This will be stored in storage for participating users.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Run the variant
     *
     * @throws TestExecutionException
     */
    public function run();
}
