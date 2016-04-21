<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Test;

use InvalidArgumentException;
use PhpAb\Exception\DuplicateVariantException;
use PhpAb\Variant\VariantInterface;

/**
 * The implementation of a Test.
 *
 * @package PhpAb
 */
class Test implements TestInterface
{
    /**
     * The identifier of this test.
     *
     * @var string
     */
    private $identifier;

    /**
     * The available variantns for this test.
     *
     * @var VariantInterface[]
     */
    private $variants;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $identifier The identifier
     * @param VariantInterface[] $variants The variants that this test has.
     */
    public function __construct($identifier, $variants = [])
    {
        if (!is_string($identifier) || $identifier === '') {
            throw new InvalidArgumentException('The provided identifier is not a valid identifier.');
        }

        $this->identifier = $identifier;
        $this->setVariants($variants);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Adds a variant to this test.
     *
     * @throws DuplicateVariantException
     *
     * @param VariantInterface $variant The variant to add to this test.
     */
    public function addVariant(VariantInterface $variant)
    {
        if (array_key_exists($variant->getIdentifier(), $this->variants)) {
            throw new DuplicateVariantException('A variant with this identifier has already been added.');
        }

        $this->variants[$variant->getIdentifier()] = $variant;
    }

    /**
     * Sets the variants in this test.
     *
     * @param VariantInterface[] $variants The variants to set.
     */
    public function setVariants($variants)
    {
        $this->variants = [];

        foreach ($variants as $variant) {
            $this->addVariant($variant);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $identifier The identifier of the variant to get.
     */
    public function getVariant($identifier)
    {
        if (!array_key_exists($identifier, $this->variants)) {
            return null;
        }

        return $this->variants[$identifier];
    }
}
