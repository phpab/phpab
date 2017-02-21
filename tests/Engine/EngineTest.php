<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Engine;

use PhpAb\Analytics\SimpleAnalytics;
use PhpAb\Filter\Percentage;
use PhpAb\Storage\RuntimeStorage;
use PhpAb\Subject;
use PhpAb\SubjectInterface;
use PhpAb\Test\Test;
use PhpAb\Chooser\IdentifierChooser;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Variant\VariantInterface;

class EngineTest extends \PHPUnit_Framework_TestCase
{
    private $alwaysParticipateFilter;
    private $chooser;
    private $variant;
    private $subject;

    public function setUp()
    {
        \phpmock\Mock::disableAll();

        $this->alwaysParticipateFilter = new Percentage(100);
        $this->chooser = new IdentifierChooser(0);

        $this->variant = $this->getMockBuilder(VariantInterface::class)
            ->setMethods(['getIdentifier', 'run'])
            ->getMock();

        $this->manager = $subject = $this->getMockBuilder(SubjectInterface::class)
            ->getMock();

        $this->subject = new Subject(new RuntimeStorage());
    }

    public function testEmptyManager()
    {
        // Arrange
        $engine = new Engine(new SimpleAnalytics());

        // Act
        $result = $engine->test($this->subject);

        // Assert
        $this->assertNull($result);
    }

    public function testAddTest()
    {
        // Arrange
        $test1 = new Test('foo');
        $test2 = new Test('bar');

        $engine = new Engine(new SimpleAnalytics());

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
        $engine = new Engine(new SimpleAnalytics());

        // Act
        $engine->getTest('foo');
    }

    /**
     * @expectedException \PhpAb\Exception\TestCollisionException
     */
    public function testAlreadyExistsWithSameName()
    {
        // Arrange
        $engine = new Engine(new SimpleAnalytics());
        $engine->addTest(new Test('foo'), $this->alwaysParticipateFilter, $this->chooser, []);

        // Act
        $engine->addTest(new Test('foo'), $this->alwaysParticipateFilter, $this->chooser, []);
    }

    public function testUserGetsNewParticipation()
    {
        // Arrange
        $test = new Test('t1', [
            new SimpleVariant('v1'),
            new SimpleVariant('v2'),
            new SimpleVariant('v3')
        ]);

        $engine = new Engine(new SimpleAnalytics());
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            new IdentifierChooser('v1'),
            []
        );

        // Act
        $engine->test($this->subject);
        $result = $this->subject->participates($engine->getTest('t1'));

        // Assert
        $this->assertTrue($result);
    }

    public function testNoVariantAvailableForTest()
    {
        // Arrange
        $test = new Test('t1');

        $engine = new Engine(new SimpleAnalytics());
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            new IdentifierChooser('v1'),
            []
        );
        $engine->test($this->subject);

        // Act
        $result = $this->subject->participates($engine->getTest('t1'));

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @expectedException \PhpAb\Exception\EngineLockedException
     */
    public function testLockEngine()
    {
        // Arrange
        $engine = new Engine(new SimpleAnalytics());

        $test = new Test('foo_test', [new SimpleVariant('_control')]);

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
        $engine = new Engine(new SimpleAnalytics());

        // Act
        $engine->test($this->getMock(SubjectInterface::class));
        $engine->test($this->getMock(SubjectInterface::class));

        // Assert
    }
}
