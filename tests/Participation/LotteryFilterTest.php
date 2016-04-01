<?php

namespace PhpAb\Participation;

use phpmock\functions\FixedValueFunction;
use phpmock\Mock;
use phpmock\MockBuilder;

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
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
            ->setName('mt_rand')
            ->setFunctionProvider(new FixedValueFunction(0));
        $mock = $builder->build();
        $mock->enable();

        $lottery = new LotteryFilter(23);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertTrue($participates);
    }

    public function testShouldParticipateWithCustomPropabilityAndNegativeResult()
    {
        // Arrange
        // Override mt_rand
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
            ->setName('mt_rand')
            ->setFunctionProvider(new FixedValueFunction(99));
        $mock = $builder->build();
        $mock->enable();

        $lottery = new LotteryFilter(23);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertFalse($participates);
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
    public function testShouldAcceptIntergerOnly()
    {
        // Arrange
        $lottery = new LotteryFilter('Walter');

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

    public function tearDown()
    {
        // disable all mocked functions
        Mock::disableAll();
    }
}
