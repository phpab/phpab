<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\Google;

use PhpAb\Analytics\DataCollector\Google;
use PHPUnit_Framework_TestCase;

class GoogleTest extends PHPUnit_Framework_TestCase
{

    public function testGetSubscribedEvents()
    {
        // Arrange
        $collector = new Google();

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
        $expData = new Google();

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
        $expData = new Google();

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
        $expData = new Google();

        // Act
        $expData->addParticipation('walter', '1');

        // Assert
        // ..
    }
    
    public function testOnRegisterParticipation()
    {
        // Arrange
        $expData = new Google();
        $expData->addParticipation('walter', 0);
        $expData->addParticipation('bernard', 1);

        // Act
        $data = $expData->getTestsData();

        // Assert
        $this->assertSame(
            [
                'walter' => 0,
                'bernard' => 1
            ],
            $data
        );
    }
}
