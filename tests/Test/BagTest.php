<?php

namespace Phpab\Phpab\Test;

use Phpab\Phpab\Participation\FilterInterface;
use Phpab\Phpab\Variant\ChooserInterface;

class BagTest extends \PHPUnit_Framework_TestCase
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
