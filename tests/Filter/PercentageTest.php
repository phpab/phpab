<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Filter;

use phpmock\functions\FixedValueFunction;
use phpmock\Mock;
use phpmock\MockBuilder;
use PHPUnit_Framework_TestCase;

class PercentageTest extends PHPUnit_Framework_TestCase
{
    public function testShouldParticipateWithFullPropability()
    {
        // Arrange
        $lottery = new Percentage(100);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertTrue($participates);
    }

    public function testShouldParticipateWithZeroPropability()
    {
        // Arrange
        $lottery = new Percentage(0);

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

        $lottery = new Percentage(23);

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

        $lottery = new Percentage(23);

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
        $lottery = new Percentage(101);

        // Act
        $lottery->shouldParticipate();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldAcceptIntergerOnly()
    {
        // Arrange
        $lottery = new Percentage('Walter');

        // Act
        $lottery->shouldParticipate();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldParticipateWithUnderPercentage()
    {
        // Arrange
        $lottery = new Percentage(-1);

        // Act
        $lottery->shouldParticipate();
    }

    public function tearDown()
    {
        // disable all mocked functions
        Mock::disableAll();
    }
}
