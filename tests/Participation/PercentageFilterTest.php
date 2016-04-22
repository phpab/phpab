<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Participation;

use phpmock\functions\FixedValueFunction;
use phpmock\Mock;
use phpmock\MockBuilder;

class PercentageFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldParticipateWithFullPropability()
    {
        // Arrange
        $lottery = new PercentageFilter(100);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertTrue($participates);
    }

    public function testShouldParticipateWithZeroPropability()
    {
        // Arrange
        $lottery = new PercentageFilter(0);

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

        $lottery = new PercentageFilter(23);

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

        $lottery = new PercentageFilter(23);

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
        $lottery = new PercentageFilter(101);

        // Act
        $lottery->shouldParticipate();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldAcceptIntergerOnly()
    {
        // Arrange
        $lottery = new PercentageFilter('Walter');

        // Act
        $lottery->shouldParticipate();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldParticipateWithUnderPercentage()
    {
        // Arrange
        $lottery = new PercentageFilter(-1);

        // Act
        $lottery->shouldParticipate();
    }

    public function tearDown()
    {
        // disable all mocked functions
        Mock::disableAll();
    }
}
