<?php

namespace PhpAb\Analytics\DB;

use PhpAb\Event\SubscriberInterface;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;
use Webmozart\Assert\Assert;

class DataCollector implements SubscriberInterface
{

    /**
     * @var array Test identifiers and variation indexes
     */
    private $participations = [];

    /**
     * @inheritDoc
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
     * @param string $testIdentifier
     * @param string $variationIdentifier
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function addParticipation($testIdentifier, $variationIdentifier)
    {
        Assert::string($testIdentifier, 'Test identifier must be a string');

        Assert::string($variationIdentifier, 'Variation name must be a string');

        $this->participations[$testIdentifier] = $variationIdentifier;
    }

    /**
     * @return array
     */
    public function getTestsData()
    {
        return $this->participations;
    }
}
