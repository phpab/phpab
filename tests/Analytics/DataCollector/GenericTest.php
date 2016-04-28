<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\DataCollector;

use PHPUnit_Framework_TestCase;

class GenericTest extends PHPUnit_Framework_TestCase
{
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
     * @expectedException InvalidArgumentException
     */
    public function addParticipationInvalidTestIdentifier()
    {
        // Arrange
        $expData = new Generic();

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
        $expData = new Generic();

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
        $expData = new Generic();

        // Act
        $expData->addParticipation('walter', '1');

        // Assert
        // ..
    }

    public function testOnRegisterParticipation()
    {
        // Arrange
        $expData = new Generic();
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
