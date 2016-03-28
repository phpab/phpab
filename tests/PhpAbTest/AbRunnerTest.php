<?php

namespace PhpAbTest;

use PhpAb\AbRunner;
use PhpAb\AbTest;
use PhpAbTestAsset\CallbackHandler;
use PhpAbTestAsset\EmptyStrategy;
use PHPUnit_Framework_TestCase;
use RuntimeException;

class AbRunnerTest extends PHPUnit_Framework_TestCase
{
    private $handler;
    private $strategy;
    private $callbackA;
    private $callbackB;

    public function setUp()
    {
        $this->handler = new CallbackHandler();
        $this->strategy = new EmptyStrategy();
        $this->callbackA = array($this->handler, 'methodA');
        $this->callbackB = array($this->handler, 'methodB');
    }

    public function testEmptyConstructor()
    {
        // Arrange
        // ...

        // Act
        $phpab = new AbRunner();

        // Assert
        $this->assertNull($phpab->getParticipationStrategy());
    }

    public function testNonEmptyConstructor()
    {
        // Arrange
        // ...

        // Act
        $phpab = new AbRunner($this->strategy);

        // Assert
        $this->assertEquals($this->strategy, $phpab->getParticipationStrategy());
    }

    public function testAddTest()
    {
        // Arrange
        $phpab = new AbRunner();

        // Act
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy));

        // Assert
        $this->assertCount(1, $phpab->getTests());
    }

    public function testSetTests()
    {
        // Arrange
        $phpab = new AbRunner();

        // Act
        $phpab->setTests(array(
            new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy)
        ));

        // Assert
        $this->assertCount(1, $phpab->getTests());
    }

    public function testSetGetAnalytics()
    {
        // Arrange
        $phpab = new AbRunner();
        $analytics = $this->getMock('PhpAb\Analytics\AnalyticsInterface');

        // Act
        $phpab->setAnalytics($analytics);

        // Assert
        $this->assertEquals($analytics, $phpab->getAnalytics());
    }

    public function testSetGetStorage()
    {
        // Arrange
        $phpab = new AbRunner();
        $storage = $this->getMock('PhpAb\Storage\StorageInterface');

        // Act
        $phpab->setStorage($storage);

        // Assert
        $this->assertEquals($storage, $phpab->getStorage());
    }

    public function testTestWithoutTests()
    {
        // Arrange
        $phpab = new AbRunner();

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(0, $executedTests);
    }

    public function testTestWithTests()
    {
        // Arrange
        $phpab = new AbRunner();
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(1, $executedTests);
    }

    public function testTestWithMultipleTests()
    {
        // Arrange
        $phpab = new AbRunner();
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy));
        $phpab->addTest(new AbTest('test2', $this->callbackA, $this->callbackB, $this->strategy));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(2, $executedTests);
    }

    public function testTestWithEmptyStorage()
    {
        // Arrange
        $phpab = new AbRunner();
        $phpab->setStorage($this->getMock('PhpAb\Storage\StorageInterface'));
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(1, $executedTests);
    }

    public function testTestWithNonEmptyStorage()
    {
        // Arrange
        $phpab = new AbRunner();
        $phpab->setStorage($this->getMock('PhpAb\Storage\StorageInterface'));
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(1, $executedTests);
    }

    public function testTestWithNonParticipatingRunner()
    {
        // Arrange
        $this->strategy->setParticipating(false);

        $phpab = new AbRunner($this->strategy);
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(0, $executedTests);
    }

    public function testTestWithNonParticipatingTest()
    {
        // Arrange
        $this->strategy->setParticipating(false);

        $phpab = new AbRunner();
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(0, $executedTests);
    }

    public function testTestWithAnalytics()
    {
        // Arrange
        $phpab = new AbRunner();
        $phpab->setAnalytics($this->getMock('PhpAb\Analytics\AnalyticsInterface'));
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(1, $executedTests);
    }

    public function testTestWithStorageAndAnalytics()
    {
        // Arrange
        $storage = $this->getMock('PhpAb\Storage\StorageInterface');
        $storage->method('read')->willReturn('B');

        $phpab = new AbRunner();
        $phpab->setAnalytics($this->getMock('PhpAb\Analytics\AnalyticsInterface'));
        $phpab->setStorage($storage);
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(1, $executedTests);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testTestWithInvalidStorage()
    {
        // Arrange
        $storage = $this->getMock('PhpAb\Storage\StorageInterface');
        $storage->method('read')->willReturn('ABC');

        $phpab = new AbRunner();
        $phpab->setStorage($storage);
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, $this->strategy));

        // Act
        $phpab->test();

        // Assert
        // ...
    }

    public function testTestTestWithoutStrategy()
    {
        // Arrange
        $phpab = new AbRunner();
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, null));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(1, $executedTests);
    }

    public function testStrategyFromRunner()
    {
        // Arrange
        $phpab = new AbRunner(new EmptyStrategy(true));
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, null));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(1, $executedTests);
    }

    public function testStrategyFromTest()
    {
        // Arrange
        $phpab = new AbRunner();
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, new EmptyStrategy(true)));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(1, $executedTests);
    }

    public function testStrategyIfNotSetInTest()
    {
        // Arrange
        $phpab = new AbRunner(new EmptyStrategy(false));
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, null));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(0, $executedTests);
    }

    public function testStrategyIfBothSet()
    {
        // Arrange
        $phpab = new AbRunner(new EmptyStrategy(false));
        $phpab->addTest(new AbTest('test', $this->callbackA, $this->callbackB, new EmptyStrategy(true)));

        // Act
        $executedTests = $phpab->test();

        // Assert
        $this->assertEquals(1, $executedTests);
    }
}
