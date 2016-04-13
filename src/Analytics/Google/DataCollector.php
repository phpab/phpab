<?php

namespace PhpAb\Analytics\Google;

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
            'phpab.participation.variant_run' => function ($options)
            {
                /** @var TestInterface $test */
                $test = $options[1]->getTest();

                /** @var VariantInterface $chosenVariant */
                $chosenVariant = $options[2];

                $variants = $test->getVariants();

                // Get the index number of the element
                $chosenIndex = array_search($chosenVariant->getIdentifier(), array_keys($variants));

                // Call the add method
                $this->addParticipation($test->getIdentifier(), $chosenIndex);
            }
        ];
    }

    /**
     * @param string $testIdentifier It will look like "Qp0gahJ3RAO3DJ18b0XoUQ"
     * @param int $variationIndex
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function addParticipation($testIdentifier, $variationIndex)
    {
        Assert::string($testIdentifier, 'Test identifier must be a string');

        Assert::integer($variationIndex, 'Variation index must be integer');

        Assert::greaterThan($variationIndex, -1, 'Variation index must be integer >= 0');

        $this->participations[$testIdentifier] = $variationIndex;
    }

    /**
     * @return array
     */
    public function getTestsData()
    {
        return $this->participations;
    }
}
