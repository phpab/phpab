<?php

namespace PhpAb\Participation;

class LotteryFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldParticipateWithFullPropability()
    {
        // Arrange
        $lottery = new LotteryFilter(100);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertTrue($participates);
    }

    public function testShouldParticipateWithZeroPropability()
    {
        // Arrange
        $lottery = new LotteryFilter(0);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertFalse($participates);
    }

    public function testShouldParticipateWithCustomPropabilityAndPositiveResult()
    {
        // Arrange
        // Override mt_rand
        function mt_rand($min, $max)
        {
            return 0;
        }

        $lottery = new LotteryFilter(23);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertTrue($participates);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldParticipateWithOverPercentage()
    {
        // Arrange
        $lottery = new LotteryFilter(101);

        // Act
        $lottery->shouldParticipate();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldParticipateWithUnderPercentage()
    {
        // Arrange
        $lottery = new LotteryFilter(-1);

        // Act
        $lottery->shouldParticipate();
    }
}
