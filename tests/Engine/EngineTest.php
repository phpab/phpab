<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Engine;

use PhpAb\Participation\Filter\FilterInterface;
use PhpAb\Participation\Filter\Percentage;
use PhpAb\Participation\Manager;
use PhpAb\Participation\ManagerInterface;
use PhpAb\Storage\Adapter\Runtime;
use PhpAb\Storage\Storage;
use PhpAb\Test\Test;
use PhpAb\Variant\Chooser\StaticChooser;
use PhpAb\Variant\Chooser\RandomChooser;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Variant\VariantInterface;
use PhpAb\Analytics\DataCollector\Google;

class EngineTest extends \PHPUnit_Framework_TestCase
{
    private $alwaysParticipateFilter;
    private $chooser;
    private $variant;
    private $manager;

    public function setUp()
    {
        \phpmock\Mock::disableAll();

        $this->alwaysParticipateFilter = new Percentage(100);
        $this->chooser = new StaticChooser(0);

        $this->variant = $this->getMockBuilder(VariantInterface::class)
            ->setMethods(['getIdentifier', 'run'])
            ->getMock();

        $this->manager = $manager = $this->getMockBuilder(ManagerInterface::class)
            ->getMock();
    }

    public function testEmptyManager()
    {
        // Arrange
        $manager = new Manager(new Storage(new Runtime()));
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
        $engine->addTest($test1, $this->alwaysParticipateFilter, $this->chooser, []);
        $engine->addTest($test2, $this->alwaysParticipateFilter, $this->chooser, []);

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
        $engine->addTest(new Test('foo'), $this->alwaysParticipateFilter, $this->chooser, []);

        // Act
        $engine->addTest(new Test('foo'), $this->alwaysParticipateFilter, $this->chooser, []);
    }

    public function testUserParticipatesVariant()
    {
        // Arrange
        $this->manager->method('participates')
            ->with('foo')
            ->willReturn(true);

        $this->manager->method('getParticipatingVariant')
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
        $engine->addTest($test, $this->alwaysParticipateFilter, new StaticChooser('bar'), []);

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
            ->willReturn(false);

        $this->variant
            ->expects($this->exactly(0))
            ->method('run');
        $this->variant
            ->method('getIdentifier')
            ->willReturn('notParticipated');

        $test = new Test('foo');
        $test->addVariant($this->variant);

        $engine = new Engine($this->manager);
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            $this->chooser,
            []
        );

        // Act
        $engine->start();

        // Assert
    }

    public function testUserParticipatesNonExistingVariant()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $storage->set('foo', 'bar');

        $manager = new Manager($storage);

        $test = new Test('foo');

        $engine = new Engine($manager);
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            $this->chooser,
            []
        );

        // Act
        $engine->start();
        $result = $manager->participates('foo', 'bar');

        // Assert
        $this->assertFalse($result);
    }

    public function testUserShouldNotParticipateWasStoredInStorage()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $storage->set('foo', null);
        $manager = new Manager($storage);

        $engine = new Engine($manager);
        $engine->addTest(
            new Test('foo'),
            $this->alwaysParticipateFilter,
            $this->chooser,
            []
        );

        // Act
        $engine->start();
        $result = $manager->participates('foo');

        // Assert
        $this->assertTrue($result);
        $this->assertNull($manager->getParticipatingVariant('foo'));
    }

    public function testUserShouldNotParticipate()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $manager = new Manager($storage);

        $engine = new Engine($manager);
        $engine->addTest(
            new Test('foo'),
            new Percentage(0),
            $this->chooser,
            []
        );

        // Act
        $engine->start();
        $result = $manager->participates('foo');

        // Assert
        $this->assertTrue($result);
        $this->assertNull($manager->getParticipatingVariant('foo'));
    }

    public function testUserShouldNotParticipateWithExistingVariant()
    {
        // Arrange
         $storage = new Storage(new Runtime());
        $manager = new Manager($storage);

        $test = new Test('foo');
        $test->addVariant(new SimpleVariant('yolo'));

        $engine = new Engine($manager);
        $engine->addTest(
            $test,
            new Percentage(0),
            $this->chooser,
            []
        );

        // Act
        $engine->start();
        $result = $manager->participates('foo');

        // Assert
        $this->assertTrue($result);
    }

    public function testUserGetsNewParticipation()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $manager = new Manager($storage);

        $test = new Test('t1');
        $test->addVariant(new SimpleVariant('v1'));
        $test->addVariant(new SimpleVariant('v2'));
        $test->addVariant(new SimpleVariant('v3'));

        $engine = new Engine($manager);
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            new StaticChooser('v1'),
            []
        );

        // Act
        $engine->start();
        $result = $manager->participates('t1');

        // Assert
        $this->assertTrue($result);
    }

    public function testNoVariantAvailableForTest()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $manager = new Manager($storage);
        $test = new Test('t1');

        $engine = new Engine($manager);
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            new StaticChooser('v1'),
            []
        );
        $engine->start();

        // Act
        $result = $manager->participates('t1');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Testing that Engine picks previous test runs values
     */
    public function testPreviousRunConsistencyInStorage()
    {
        // Arrange
        $storage = new Storage(
            new Runtime(
                [
                    'foo_test' => 'v1',
                    'bar_test' => '_control'
                ]
            )
        );
        $manager = new Manager($storage);
        $analyticsData = new Google();

        $filter = new Percentage(5);
        $chooser = new RandomChooser();

        $engine = new Engine($manager, $filter, $chooser);
        $engine->addSubscriber($analyticsData);

        $test = new Test('foo_test', [], [Google::EXPERIMENT_ID => 'EXPID1']);
        $test->addVariant(new SimpleVariant('_control'));
        $test->addVariant(new SimpleVariant('v1'));
        $test->addVariant(new SimpleVariant('v2'));

        $test2 = new Test('bar_test', [], [Google::EXPERIMENT_ID => 'EXPID2']);
        $test2->addVariant(new SimpleVariant('_control'));
        $test2->addVariant(new SimpleVariant('v1'));

        $engine->addTest($test, $this->alwaysParticipateFilter, $this->chooser);
        $engine->addTest($test2, $this->alwaysParticipateFilter, $this->chooser);

        $engine->start();

        // Act
        $testData = $analyticsData->getTestsData();

        // Assert
        $this->assertSame(
            [
            'EXPID1' => 1,
            'EXPID2' => 0
            ],
            $testData
        );
    }

    /**
     * @expectedException \PhpAb\Exception\EngineLockedException
     */
    public function testLockEngine()
    {
        // Arrange
        $engine = new Engine(
            $this->getMock(ManagerInterface::class)
        );

        $test = new Test('foo_test');
        $test->addVariant(new SimpleVariant('_control'));

        // Act
        $engine->start();
        $engine->addTest($test, $this->alwaysParticipateFilter, $this->chooser);

        // Assert
    }

    /**
     * @expectedException \PhpAb\Exception\EngineLockedException
     */
    public function testStartTwice()
    {
        // Arrange
        $engine = new Engine(
            $this->getMock(ManagerInterface::class),
            $this->getMock(FilterInterface::class),
            null // This is the tested part
        );

        $test = new Test('foo_test');
        $test->addVariant(new SimpleVariant('_control'));

        // Act
        $engine->start();
        $engine->start();

        // Assert
    }
}
