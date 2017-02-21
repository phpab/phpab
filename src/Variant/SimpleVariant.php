<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link      https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license   https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant;

/**
 * SimpleVariant does not perform any action on run()
 * It's simple a named Variant
 *
 * It can be used for example for
 * - Control-Group
 * - Simple Frontend-Tests
 *
 * @package PhpAb
 */
class SimpleVariant implements VariantInterface
{
    /**
     * The identifier of the variant.
     *
     * @var string
     */
    private $identifier;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $identifier The Identifier of the Variant
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * {@inheritDoc}
     */
    public function run()
    {
        // no return to comply with the interface
    }
}
