<?php

namespace PhpAb\Engine;

use PhpAb\Participation\Manager;
use PhpAb\Participation\ParticipationManagerInterface;
use PhpAb\Participation\PercentageFilter;
use PhpAb\Storage\Runtime;
use PhpAb\Test\Test;
use PhpAb\Variant\RandomChooser;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Variant\VariantInterface;

class EngineTest extends \PHPUnit_Framework_TestCase
{
    private $alwaysParticipateFilter;
    private $chooser;
    private $variant;
    private $manager;

    public function setUp()
    {
        $this->alwaysParticipateFilter = new PercentageFilter(100);
        $this->chooser = new RandomChooser();

        $this->variant = $this->getMockBuilder(VariantInterface::class)
            ->setMethods(['getIdentifier', 'run'])
            ->getMock();

        $this->manager = $manager = $this->getMockBuilder(ParticipationManagerInterface::class)
            ->getMock();
    }

    public function testEmptyManager()
    {
        // Arrange
        $manager = new Manager(new Runtime());
        $engine = new Engine($manager);

        // Act
        $result = $engine->start();

        // Assert
        $this->assertNull($result);
    }

    public function testAddTest()
    {
        // Arrange
        $test1 = new Test('foo');
        $test2 = new Test('bar');

        $engine = new Engine($this->manager);

        // Act
        $engine->addTest($test1, [], $this->alwaysParticipateFilter, $this->chooser);
        $engine->addTest($test2, [], $this->alwaysParticipateFilter, $this->chooser);

        // Assert
        $this->assertSame($test1, $engine->getTest('foo'));
        $this->assertSame($test2, $engine->getTest('bar'));
        $this->assertCount(2, $engine->getTests());
    }

    /**
     * @expectedException \PhpAb\Exception\TestNotFoundException
     */
    public function testGetTestNotFound()
    {
        // Arrange
        $engine = new Engine($this->manager);

        // Act
        $engine->getTest('foo');
    }

    /**
     * @expectedException \PhpAb\Exception\TestCollisionException
     */
    public function testAlreadyExistsWithSameName()
    {
        // Arrange
        $engine = new Engine($this->manager);
        $engine->addTest(new Test('foo'), [], $this->alwaysParticipateFilter, $this->chooser);

        // Act
        $engine->addTest(new Test('foo'), [], $this->alwaysParticipateFilter, $this->chooser);
    }

    public function testUserParticipatesVariant()
    {
        // Arrange
        $this->manager->method('participates')
            ->with('foo')
            ->willReturn('bar');

        $this->variant
            ->expects($this->once())
            ->method('run');
        $this->variant
            ->method('getIdentifier')
            ->willReturn('bar');

        $test = new Test('foo');
        $test->addVariant($this->variant);

        $engine = new Engine($this->manager);
        $engine->addTest($test, [], $this->alwaysParticipateFilter, $this->chooser);

        // Act
        $result = $engine->start();

        // Assert
        $this->assertNull($result);
    }

    public function testUserDoesNotParticipateVariant()
    {
        // Arrange
        $this->manager->method('participates')
            ->with('foo')
            ->willReturn('bar');

        $this->variant
            ->expects($this->exactly(0))
            ->method('run');
        $this->variant
            ->method('getIdentifier')
            ->willReturn('notParticipated');

        $test = new Test('foo');
        $test->addVariant($this->variant);

        $engine = new Engine($this->manager);
        $engine->addTest($test, [], $this->alwaysParticipateFilter, $this->chooser);

        // Act
        $result = $engine->start();

        // Assert
    }

    public function testUserParticipatesNonExistingVariant()
    {
        // Arrange
        $storage = new Runtime();
        $storage->set('foo', 'bar');

        $manager = new Manager($storage);

        $test = new Test('foo');

        $engine = new Engine($manager);
        $engine->addTest($test, [], $this->alwaysParticipateFilter, $this->chooser);

        // Act
        $engine->start();
        $result = $manager->participates('foo', null);

        // Assert
        $this->assertNull($result);
    }

    public function testUserShouldNotParticipateWasStoredInStorage()
    {
        // Arrange
        $storage = new Runtime();
        $storage->set('foo', null);
        $manager = new Manager($storage);

        $engine = new Engine($manager);
        $engine->addTest(new Test('foo'), [], $this->alwaysParticipateFilter, $this->chooser);

        // Act
        $engine->start();
        $result = $manager->participates('foo');

        // Assert
        $this->assertNull($result);
    }

    public function testUserShouldNotParticipate()
    {
        // Arrange
        $storage = new Runtime();
        $manager = new Manager($storage);

        $engine = new Engine($manager);
        $engine->addTest(new Test('foo'), [], new PercentageFilter(0), $this->chooser);

        // Act
        $engine->start();
        $result = $manager->participates('foo');

        // Assert
        $this->assertNull($result);
    }

    public function testUserShouldNotParticipateWithExistingVariant()
    {
        // Arrange
        $storage = new Runtime();
        $manager = new Manager($storage);

        $test = new Test('foo');
        $test->addVariant(new SimpleVariant('yolo'));

        $engine = new Engine($manager);
        $engine->addTest($test, [], new PercentageFilter(0), $this->chooser);

        // Act
        $engine->start();
        $result = $manager->participates('foo');

        // Assert
        $this->assertNull($result);
    }

    public function testUserGetsNewParticipation()
    {
        $this->markTestIncomplete('Check if ParticipationManager was adjusted');
        $this->markTestIncomplete('Check if the variant got executed');
    }

    public function testNoVariantAvailableForTest()
    {
        $this->markTestIncomplete('Must be implemented');
    }
}
