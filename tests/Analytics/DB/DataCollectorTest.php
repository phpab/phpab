<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\DB;

use PhpAb\Test\Test;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Test\Bag;
use PhpAb\Participation\PercentageFilter;
use PhpAb\Variant\RandomChooser;

class DataCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSubscribedEvents()
    {
        // Arrange
        $collector = new DataCollector();

        // Act
        $result = $collector->getSubscribedEvents();

        // Assert
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('phpab.participation.variant_run', $result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function addParticipationInvalidTestIdentifier()
    {
        // Arrange
        $dataCollector = new DataCollector();

        // Act
        $dataCollector->addParticipation(987, 1);

        // Assert
        // ..
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function addParticipationInvalidVariationIndexRange()
    {
        // Arrange
        $dataCollector = new DataCollector();

        // Act
        $dataCollector->addParticipation('walter', -1);

        // Assert
        // ..
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function addParticipationInvalidVariationNotInt()
    {
        // Arrange
        $dataCollector = new DataCollector();

        // Act
        $dataCollector->addParticipation('walter', '1');

        // Assert
        // ..
    }

    public function testOnRegisterParticipation()
    {
        // Arrange
        $dataCollector = new DataCollector();
        $dataCollector->addParticipation('walter', 'white');
        $dataCollector->addParticipation('bernard', 'black');

        // Act
        $data = $dataCollector->getTestsData();

        // Assert
        $this->assertSame(
            [
                'walter' => 'white',
                'bernard' => 'black'
            ],
            $data
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetSubscribedEventsEmptyOptions()
    {
        // Arrange
        $dataCollector = new DataCollector();
        $event = $dataCollector->getSubscribedEvents();

        // Act
        call_user_func($event['phpab.participation.variant_run'], []);

        // Assert
        // ..
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetSubscribedEventsNoBag()
    {
        // Arrange
        $dataCollector = new DataCollector();
        $event = $dataCollector->getSubscribedEvents();

        // Act
        call_user_func(
            $event['phpab.participation.variant_run'],
            [
                0 => 'foo'
            ]
        );

        // Assert
        // ..
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetSubscribedEventsNoBagInstance()
    {
        // Arrange
        $dataCollector = new DataCollector();
        $event = $dataCollector->getSubscribedEvents();

        // Act
        call_user_func(
            $event['phpab.participation.variant_run'],
            [
                0 => 'foo',
                1 => new \DateTime
            ]
        );

        // Assert
        // ..
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetSubscribedEventsNoVariant()
    {
        // Arrange
        $dataCollector = new DataCollector();
        $event = $dataCollector->getSubscribedEvents();
        $bag = new Bag(
            new Test('Bernard'),
            new PercentageFilter(100),
            new RandomChooser
        );

        // Act
        call_user_func(
            $event['phpab.participation.variant_run'],
            [
                0 => 'foo',
                1 => $bag
            ]
        );

        // Assert
        // ..
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetSubscribedEventsNoVariantInstance()
    {
        // Arrange
        $dataCollector = new DataCollector();
        $eventCallback = $dataCollector->getSubscribedEvents();
        $bag = new Bag(
            new Test('Bernard'),
            new PercentageFilter(100),
            new RandomChooser
        );

        // Act
        call_user_func(
            $eventCallback['phpab.participation.variant_run'],
            [
                0 => 'foo',
                1 => $bag,
                2 => new \DateTime
            ]
        );

        // Assert
        // ..
    }

    public function testRunEvent()
    {
        // Arrange
        $dataCollector = new DataCollector();
        $eventCallback = $dataCollector->getSubscribedEvents();
        $bag = new Bag(
            new Test('Bernard'),
            new PercentageFilter(100),
            new RandomChooser
        );

        // Act
        call_user_func(
            $eventCallback['phpab.participation.variant_run'],
            [
                0 => 'foo',
                1 => $bag,
                2 => new SimpleVariant('Black')
            ]
        );

        $participations = $dataCollector->getTestsData();

        // Assert
        $this->assertSame(
            ['Bernard' => 'Black'],
            $participations
        );
    }
}
