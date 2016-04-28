<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\DataCollector;

use PhpAb\Event\SubscriberInterface;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;
use Webmozart\Assert\Assert;

/**
 * A generic data collector that holds information about which tests have been executed.
 *
 * @package PhpAb
 */
class Generic implements SubscriberInterface
{
    /**
     * @var array Test identifiers and variation indexes
     */
    private $participations = [];

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return [
            'phpab.participation.variant_run' => function ($options) {

                /** @var TestInterface $test */
                $test = $options[1]->getTest();

                /** @var VariantInterface $chosenVariant */
                $chosenVariant = $options[2];

                // Call the add method
                $this->addParticipation($test->getIdentifier(), $chosenVariant->getIdentifier());
            }
        ];
    }

    /**
     * Adds a participation to the data collector.
     *
     * @param string $testIdentifier
     * @param string $variationIdentifier
     * @throws InvalidArgumentException
     */
    public function addParticipation($testIdentifier, $variationIdentifier)
    {
        Assert::string($testIdentifier, 'Test identifier must be a string');
        Assert::string($variationIdentifier, 'Variation name must be a string');

        $this->participations[$testIdentifier] = $variationIdentifier;
    }

    /**
     * Gets all the data that has been collected.
     *
     * @return array
     */
    public function getTestsData()
    {
        return $this->participations;
    }
}
