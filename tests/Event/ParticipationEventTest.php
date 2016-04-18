<?php

namespace PhpAb\Event;

use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;

class ParticipationEventTest extends \PHPUnit_Framework_TestCase
{
    private $test;
    private $variant;

    public function setUp()
    {
        $this->test = $this->getMock(TestInterface::class);
        $this->variant = $this->getMock(VariantInterface::class);
    }

    public function testGetTest()
    {
        // Arrange
        $event = new ParticipationEvent($this->test, $this->variant, false);

        // Act
        $result = $event->getTest();

        // Assert
        $this->assertSame($this->test, $result);
    }

    public function testGetVariant()
    {
        // Arrange
        $event = new ParticipationEvent($this->test, $this->variant, false);

        // Act
        $result = $event->getVariant();

        // Assert
        $this->assertSame($this->variant, $result);
    }

    public function testIsNotNew()
    {
        // Arrange
        $event = new ParticipationEvent($this->test, $this->variant, false);

        // Act
        $result = $event->isNew();

        // Assert
        $this->assertFalse($result);
    }

    public function testIsNew()
    {
        // Arrange
        $event = new ParticipationEvent($this->test, $this->variant, true);

        // Act
        $result = $event->isNew();

        // Assert
        $this->assertTrue($result);
    }
}
