<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\DataCollector;

use PhpAb\Test\Bag;
use PhpAb\Test\Test;
use PhpAb\Participation\Filter\Percentage;
use PhpAb\Variant\Chooser\RandomChooser;
use PhpAb\Variant\SimpleVariant;
use PHPUnit_Framework_TestCase;

class GenericTest extends PHPUnit_Framework_TestCase
{
    /**
     * Testing that getSubscribedEvents() will return an array
     * containing the closure to be executed on "phpab.participation.variant_run"
     */
    public function testGetSubscribedEvents()
    {
        // Arrange
        $collector = new Generic();

        // Act
        $result = $collector->getSubscribedEvents();

        // Assert
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('phpab.participation.variant_run', $result);
    }

    /**
     * Testing that addParticipation() accepts only string parameters
     *
     * @expectedException InvalidArgumentException
     */
    public function testAddParticipationInvalidTestIdentifier()
    {
        // Arrange
        $collector = new Generic();

        // Act
        $collector->addParticipation(987, 1);

        // Assert
    }

    /**
     * Testing that addParticipation() accepts only string parameters
     *
     * @expectedException InvalidArgumentException
     */
    public function testAddParticipationInvalidVariationIndexRange()
    {
        // Arrange
        $collector = new Generic();

        // Act
        $collector->addParticipation('walter', -1);

        // Assert
    }

    /**
     * Testing that getTestsData() returns the data injected
     * via addParticipation()
     */
    public function testOnRegisterParticipation()
    {
        // Arrange
        $collector = new Generic();
        $collector->addParticipation('walter', 'white');
        $collector->addParticipation('bernard', 'black');

        // Act
        $data = $collector->getTestsData();

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
     * Testing that the closure returned by getSubscribedEvents()
     * requires a non empty array
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGetSubscribedEventsEmptyOptions()
    {
        // Arrange
        $collector = new Generic();
        $event = $collector->getSubscribedEvents();

        // Act
        call_user_func($event['phpab.participation.variant_run'], []);

        // Assert
    }

    /**
     * Testing that the closure returned by getSubscribedEvents()
     * requires an array with size > 1
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGetSubscribedEventsNoBag()
    {
        // Arrange
        $collector = new Generic();
        $event = $collector->getSubscribedEvents();

        // Act
        call_user_func(
            $event['phpab.participation.variant_run'],
            [
                0 => 'foo'
            ]
        );

        // Assert
    }

    /**
     * Testing that the closure returned by getSubscribedEvents()
     * requires a Bag object passed in key 1
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGetSubscribedEventsNoBagInstance()
    {
        // Arrange
        $collector = new Generic();
        $event = $collector->getSubscribedEvents();

        // Act
        call_user_func(
            $event['phpab.participation.variant_run'],
            [
                0 => 'foo',
                1 => new \DateTime
            ]
        );

        // Assert
    }

    /**
     * Testing that the closure returned by getSubscribedEvents()
     * requires an array with size > 2
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGetSubscribedEventsNoVariant()
    {
        // Arrange
        $collector = new Generic();
        $event = $collector->getSubscribedEvents();
        $bag = new Bag(
            new Test('Bernard'),
            new Percentage(100),
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
    }

    /**
     * Testing that the closure returned by getSubscribedEvents()
     * requires an array with an instance of VariantInterface
     * in key 2
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGetSubscribedEventsNoVariantInstance()
    {
        // Arrange
        $collector = new Generic();
        $eventCallback = $collector->getSubscribedEvents();
        $bag = new Bag(
            new Test('Bernard'),
            new Percentage(100),
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
    }

    /**
     * Testing that the closure returned by getSubscribedEvents()
     * fills correctly the participation array
     */
    public function testRunEvent()
    {
        // Arrange
        $collector = new Generic();
        $eventCallback = $collector->getSubscribedEvents();
        $bag = new Bag(
            new Test('Bernard'),
            new Percentage(100),
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

        $participations = $collector->getTestsData();

        // Assert
        $this->assertSame(
            ['Bernard' => 'Black'],
            $participations
        );
    }
}
