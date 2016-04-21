<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Test;

use PhpAb\Participation\FilterInterface;
use PhpAb\Variant\ChooserInterface;
use PHPUnit_Framework_TestCase;

class BagTest extends PHPUnit_Framework_TestCase
{
    private $test;
    private $participationFilter;
    private $variantChooser;

    public function setUp()
    {
        $this->test = $this->getMock(TestInterface::class);
        $this->participationFilter = $this->getMock(FilterInterface::class);
        $this->variantChooser = $this->getMock(ChooserInterface::class);
    }

    public function testGetTest()
    {
        // Arrange
        $bag = new Bag($this->test, $this->participationFilter, $this->variantChooser, []);

        // Act
        $test = $bag->getTest();

        // Assert
        $this->assertInstanceOf(TestInterface::class, $test);
    }

    public function testGetOptions()
    {
        // Arrange
        $bag = new Bag($this->test, $this->participationFilter, $this->variantChooser, ['Walter']);

        // Act
        $options = $bag->getOptions();

        // Assert
        $this->assertEquals(['Walter'], $options);
    }

    public function testGetOptionsIfNotProvided()
    {
        // Arrange
        $bag = new Bag($this->test, $this->participationFilter, $this->variantChooser);

        // Act
        $options = $bag->getOptions();

        // Assert
        $this->assertEquals([], $options);
    }

    public function testGetParticipationFilter()
    {
        // Arrange
        $bag = new Bag($this->test, $this->participationFilter, $this->variantChooser);

        // Act
        $filter = $bag->getParticipationFilter();

        // Assert
        $this->assertInstanceOf(FilterInterface::class, $filter);
    }

    public function testGetVariantChooser()
    {
        // Arrange
        $bag = new Bag($this->test, $this->participationFilter, $this->variantChooser);

        // Act
        $chooser = $bag->getVariantChooser();

        // Assert
        $this->assertInstanceOf(ChooserInterface::class, $chooser);
    }
}
