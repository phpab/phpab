<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2005-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\DB;

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
        $expData = new DataCollector();

        // Act
        $expData->addParticipation(987, 1);

        // Assert
        // ..
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function addParticipationInvalidVariationIndexRange()
    {
        // Arrange
        $expData = new DataCollector();

        // Act
        $expData->addParticipation('walter', -1);

        // Assert
        // ..
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function addParticipationInvalidVariationNotInt()
    {
        // Arrange
        $expData = new DataCollector();

        // Act
        $expData->addParticipation('walter', '1');

        // Assert
        // ..
    }

    public function testOnRegisterParticipation()
    {
        // Arrange
        $expData = new DataCollector();
        $expData->addParticipation('walter', 'white');
        $expData->addParticipation('bernard', 'black');

        // Act
        $data = $expData->getTestsData();

        // Assert
        $this->assertSame(
            [
                'walter' => 'white',
                'bernard' => 'black'
            ],
            $data
        );
    }
}
