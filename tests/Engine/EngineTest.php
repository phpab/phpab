<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Engine;

use PhpAb\Event\Dispatcher;
use PhpAb\Event\DispatcherInterface;
use PhpAb\Participation\FilterInterface;
use PhpAb\Participation\Manager;
use PhpAb\Participation\ParticipationManagerInterface;
use PhpAb\Participation\PercentageFilter;
use PhpAb\Storage\Runtime;
use PhpAb\Storage\Cookie;
use PhpAb\Test\Test;
use PhpAb\Variant\ChooserInterface;
use PhpAb\Variant\StaticChooser;
use PhpAb\Variant\RandomChooser;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Variant\VariantInterface;
use PhpAb\Analytics\Google\DataCollector;
use phpmock\MockBuilder;
use phpmock\functions\FixedValueFunction;

class EngineTest extends \PHPUnit_Framework_TestCase
{
    private $alwaysParticipateFilter;
    private $chooser;
    private $variant;
    private $manager;

    public function setUp()
    {
        \phpmock\Mock::disableAll();

        $this->alwaysParticipateFilter = new PercentageFilter(100);
        $this->chooser = new StaticChooser(0);

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
        $engine = new Engine($manager, new Dispatcher());

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

        $engine = new Engine($this->manager, new Dispatcher());

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
        $engine = new Engine($this->manager, new Dispatcher());

        // Act
        $engine->getTest('foo');
    }

    /**
     * @expectedException \PhpAb\Exception\TestCollisionException
     */
    public function testAlreadyExistsWithSameName()
    {
        // Arrange
        $engine = new Engine($this->manager, new Dispatcher());
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

        $engine = new Engine($this->manager, new Dispatcher());
        $engine->addTest($test, [], $this->alwaysParticipateFilter, new StaticChooser('bar'));

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

        $engine = new Engine($this->manager, new Dispatcher());
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

        $engine = new Engine($manager, new Dispatcher());
        $engine->addTest($test, [], $this->alwaysParticipateFilter, $this->chooser);

        // Act
        $engine->start();
        $result = $manager->participates('foo', 'bar');

        // Assert
        $this->assertFalse($result);
    }

    public function testUserShouldNotParticipateWasStoredInStorage()
    {
        // Arrange
        $storage = new Runtime();
        $storage->set('foo', null);
        $manager = new Manager($storage);

        $engine = new Engine($manager, new Dispatcher());
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

        $engine = new Engine($manager, new Dispatcher());
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

        $engine = new Engine($manager, new Dispatcher());
        $engine->addTest($test, [], new PercentageFilter(0), $this->chooser);

        // Act
        $engine->start();
        $result = $manager->participates('foo');

        // Assert
        $this->assertNull($result);
    }

    public function testUserGetsNewParticipation()
    {
        // Arrange
        $storage = new Runtime();
        $manager = new Manager($storage);

        $test = new Test('t1');
        $test->addVariant(new SimpleVariant('v1'));
        $test->addVariant(new SimpleVariant('v2'));
        $test->addVariant(new SimpleVariant('v3'));

        $engine = new Engine($manager, new Dispatcher());
        $engine->addTest($test, [], $this->alwaysParticipateFilter, new StaticChooser('v1'));

        // Act
        $engine->start();
        $result = $manager->participates('t1');

        // Assert
        $this->assertEquals('v1', $result);
    }

    public function testNoVariantAvailableForTest()
    {
        // Arrange
        $storage = new Runtime();
        $manager = new Manager($storage);
        $test = new Test('t1');

        $engine = new Engine($manager, new Dispatcher());
        $engine->addTest($test, [], $this->alwaysParticipateFilter, new StaticChooser('v1'));
        $engine->start();

        // Act
        $result = $manager->participates('t1');

        // Assert
        $this->assertNull($result);
    }

    public function testPreviousRunConsistencyInCookie()
    {
        // Arrange
        $builder = new MockBuilder();
        $builder->setNamespace('PhpAb\Storage')
                ->setName("headers_sent")
                ->setFunctionProvider(new FixedValueFunction(false));
        $headersSentMock = $builder->build();

        $builder->setNamespace('PhpAb\Storage')
                ->setName("setcookie")
                ->setFunctionProvider(new FixedValueFunction(true));
        $setcookieMock = $builder->build();

        $headersSentMock->enable();
        $setcookieMock->enable();

        $_COOKIE['phpab'] = '{"foo_test":"v1","bar_test":"_control"}';
        $storage = new Cookie('phpab');
        $manager = new Manager($storage);

        $analyticsData = new DataCollector;

        $dispatcher = new Dispatcher;
        $dispatcher->addSubscriber($analyticsData);

        $filter = new PercentageFilter(5);
        $chooser = new RandomChooser();

        $engine = new Engine($manager, $dispatcher, $filter, $chooser);

        $test = new Test('foo_test');
        $test->addVariant(new SimpleVariant('_control'));
        $test->addVariant(new SimpleVariant('v1'));
        $test->addVariant(new SimpleVariant('v2'));

        $test2 = new Test('bar_test');
        $test2->addVariant(new SimpleVariant('_control'));
        $test2->addVariant(new SimpleVariant('v1'));

        $engine->addTest($test);
        $engine->addTest($test2);

        $engine->start();

        // Act
        $testData = $analyticsData->getTestsData();

        // Assert
        $this->assertSame(
            [
                'foo_test' => 1,
                'bar_test' => 0
            ],
            $testData
        );

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNoFilterThrowsException()
    {
        // Arrange
        $engine = new Engine(
            $this->getMock(ParticipationManagerInterface::class),
            $this->getMock(DispatcherInterface::class),
            null, // This is the tested part
            $this->getMock(ChooserInterface::class)
        );

        $test = new Test('foo_test');
        $test->addVariant(new SimpleVariant('_control'));

        // Act
        $engine->addTest($test);

        // Assert
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNoChooserThrowsException()
    {
        // Arrange
        $engine = new Engine(
            $this->getMock(ParticipationManagerInterface::class),
            $this->getMock(DispatcherInterface::class),
            $this->getMock(FilterInterface::class),
            null // This is the tested part
        );

        $test = new Test('foo_test');
        $test->addVariant(new SimpleVariant('_control'));

        // Act
        $engine->addTest($test);

        // Assert
    }
}
