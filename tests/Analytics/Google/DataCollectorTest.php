<?php

namespace PhpAb\Analytics\Google;

class DataCollectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException InvalidArgumentException
     */
    public function onRegisterParticipationInvalidTestIdentifier()
    {
        // Arrange
        $expData = new DataCollector();

        // Act
        $expData->onRegisterParticipation(987, 1);

        // Assert
        // ..
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function onRegisterParticipationInvalidVariationIndexRange()
    {
        // Arrange
        $expData = new DataCollector();

        // Act
        $expData->onRegisterParticipation('walter', -1);

        // Assert
        // ..
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function onRegisterParticipationInvalidVariationNotInt()
    {
        // Arrange
        $expData = new DataCollector();

        // Act
        $expData->onRegisterParticipation('walter', '1');

        // Assert
        // ..
    }

    public function testOnRegisterParticipation()
    {
        // Arrange
        $expData = new DataCollector();
        $expData->onRegisterParticipation('walter', 0);
        $expData->onRegisterParticipation('bernard', 1);

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
