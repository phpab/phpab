<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Engine;

use PhpAb\Filter\Percentage;
use PhpAb\Participation\Manager;
use PhpAb\Participation\ManagerInterface;
use PhpAb\Storage\Adapter\Runtime;
use PhpAb\Storage\Storage;
use PhpAb\Subject;
use PhpAb\SubjectInterface;
use PhpAb\Test\Test;
use PhpAb\Chooser\IdentifierChooser;
use PhpAb\Chooser\RandomChooser;
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
        $this->chooser = new IdentifierChooser(0);

        $this->variant = $this->getMockBuilder(VariantInterface::class)
            ->setMethods(['getIdentifier', 'run'])
            ->getMock();

        $this->manager = $manager = $this->getMockBuilder(SubjectInterface::class)
            ->getMock();
    }

    public function testEmptyManager()
    {
        // Arrange
        $manager = new Subject(new Storage(new Runtime()));
        $engine = new Engine();

        // Act
        $result = $engine->test($manager);

        // Assert
        $this->assertNull($result);
    }

    public function testAddTest()
    {
        // Arrange
        $test1 = new Test('foo');
        $test2 = new Test('bar');

        $engine = new Engine();

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
        $engine->addTest($test, $this->alwaysParticipateFilter, new IdentifierChooser('bar'), []);

        // Act
        $result = $engine->test($this->manager);

        // Assert
        $this->assertNull($result);
    }

    public function testUserParticipatesNonExistingVariant()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $storage->set('foo', 'bar');

        $manager = new Subject($storage);

        $test = new Test('foo');

        $engine = new Engine();
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            $this->chooser,
            []
        );

        // Act
        $engine->test($manager);
        $result = $manager->participates('foo', 'bar');

        // Assert
        $this->assertFalse($result);
    }

    public function testUserShouldNotParticipateWasStoredInStorage()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $storage->set('foo', null);
        $manager = new Subject($storage);

        $engine = new Engine();
        $engine->addTest(
            new Test('foo'),
            $this->alwaysParticipateFilter,
            $this->chooser,
            []
        );

        // Act
        $engine->test($manager);
        $result = $manager->participates('foo');

        // Assert
        $this->assertTrue($result);
        $this->assertNull($manager->getParticipatingVariant('foo'));
    }

    public function testUserShouldNotParticipate()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $manager = new Subject($storage);

        $engine = new Engine();
        $engine->addTest(
            new Test('foo'),
            new Percentage(0),
            $this->chooser,
            []
        );

        // Act
        $engine->test($manager);
        $result = $manager->participates('foo');

        // Assert
        $this->assertTrue($result);
        $this->assertNull($manager->getParticipatingVariant('foo'));
    }

    public function testUserShouldNotParticipateWithExistingVariant()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $manager = new Subject($storage);

        $test = new Test('foo');
        $test->addVariant(new SimpleVariant('yolo'));

        $engine = new Engine();
        $engine->addTest(
            $test,
            new Percentage(0),
            $this->chooser,
            []
        );

        // Act
        $engine->test($manager);
        $result = $manager->participates('foo');

        // Assert
        $this->assertTrue($result);
    }

    public function testUserGetsNewParticipation()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $manager = new Subject($storage);

        $test = new Test('t1');
        $test->addVariant(new SimpleVariant('v1'));
        $test->addVariant(new SimpleVariant('v2'));
        $test->addVariant(new SimpleVariant('v3'));

        $engine = new Engine();
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            new IdentifierChooser('v1'),
            []
        );

        // Act
        $engine->test($manager);
        $result = $manager->participates('t1');

        // Assert
        $this->assertTrue($result);
    }

    public function testNoVariantAvailableForTest()
    {
        // Arrange
        $storage = new Storage(new Runtime());
        $manager = new Subject($storage);
        $test = new Test('t1');

        $engine = new Engine();
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            new IdentifierChooser('v1'),
            []
        );
        $engine->test($manager);

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
        $manager = new Subject($storage);
        $analyticsData = new Google();

        $filter = new Percentage(5);
        $chooser = new RandomChooser();

        $engine = new Engine();
        $engine->addSubscriber($analyticsData);

        $test = new Test('foo_test', [], [Google::EXPERIMENT_ID => 'EXPID1']);
        $test->addVariant(new SimpleVariant('_control'));
        $test->addVariant(new SimpleVariant('v1'));
        $test->addVariant(new SimpleVariant('v2'));

        $test2 = new Test('bar_test', [], [Google::EXPERIMENT_ID => 'EXPID2']);
        $test2->addVariant(new SimpleVariant('_control'));
        $test2->addVariant(new SimpleVariant('v1'));

        $engine->addTest($test, $filter, $chooser);
        $engine->addTest($test2, $filter, $chooser);

        $engine->test($manager);

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
        $engine = new Engine();

        $test = new Test('foo_test');
        $test->addVariant(new SimpleVariant('_control'));

        // Act
        $engine->test($this->getMock(SubjectInterface::class));
        $engine->addTest($test, $this->alwaysParticipateFilter, $this->chooser);

        // Assert
    }

    /**
     * @expectedException \PhpAb\Exception\EngineLockedException
     */
    public function testStartTwice()
    {
        // Arrange
        $engine = new Engine();

        // Act
        $engine->test($this->getMock(SubjectInterface::class));
        $engine->test($this->getMock(SubjectInterface::class));

        // Assert
    }
}
