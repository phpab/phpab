<?php

namespace PhpAb\Analytics;

class UAExperimentDataTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException InvalidArgumentException
     */
    public function OnRegisterParticipationInvalidTestIdentifier()
    {
        // Arrange
        $expData = new UAExperimentData();

        // Act
        $expData->onRegisterParticipation(987, 1);

        // Assert
        // ..
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function OnRegisterParticipationInvalidVariationIndexRange()
    {
        // Arrange
        $expData = new UAExperimentData();

        // Act
        $expData->onRegisterParticipation('walter', -1);

        // Assert
        // ..
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function OnRegisterParticipationInvalidVariationNotInt()
    {
        // Arrange
        $expData = new UAExperimentData();

        // Act
        $expData->onRegisterParticipation('walter', "1");

        // Assert
        // ..
    }

    public function testOnRegisterParticipation()
    {
        // Arrange
        $expData = new UAExperimentData();
        $expData->onRegisterParticipation('walter', 0);
        $expData->onRegisterParticipation('bernard', 1);

        // Act
        $data = $expData->getTestsData();

        // Assert
        $this->assertSame([
            'walter' => 0,
            'bernard' => 1
            ], $data);
    }
}
