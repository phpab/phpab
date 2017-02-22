<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link      https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license   https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Engine;

use PhpAb\Analytics\AnalyticsInterface;
use PhpAb\Analytics\SimpleAnalytics;
use PhpAb\Chooser\RandomChooser;
use PhpAb\Filter\Percentage;
use PhpAb\Storage\RuntimeStorage;
use PhpAb\Subject;
use PhpAb\SubjectInterface;
use PhpAb\Test\Test;
use PhpAb\Chooser\IdentifierChooser;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Variant\VariantInterface;
use PHPUnit\Framework\TestCase;

class EngineTest extends TestCase
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
    }

    public function testEmptyManager()
    {
        // Arrange
        $engine = new Engine(new SimpleAnalytics());

        // Act
        $result = $engine->test(new Subject(new RuntimeStorage()));

        // Assert
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function add_tests()
    {
        // Arrange
        $test1 = new Test('foo', [new SimpleVariant('v1')]);
        $test2 = new Test('bar', [new SimpleVariant('v1')]);

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
        $engine->addTest(new Test('foo', [new SimpleVariant('v1')]), $this->alwaysParticipateFilter, $this->chooser, []);

        // Act
        $engine->addTest(new Test('foo', [new SimpleVariant('v1')]), $this->alwaysParticipateFilter, $this->chooser, []);
    }

    /**
     * @test
     */
    public function user_gets_new_participation()
    {
        // Arrange
        $test = new Test(
            't1',
            [
            new SimpleVariant('v1'),
            new SimpleVariant('v2'),
            new SimpleVariant('v3')
            ]
        );

        $engine = new Engine(new SimpleAnalytics());
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            new IdentifierChooser('v1'),
            []
        );

        $subject = new Subject(new RuntimeStorage());

        // Act
        $engine->test($subject);
        $result = $subject->participates($engine->getTest('t1'));

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function remembered_participation()
    {
        $variant = new SimpleVariant('v1');
        $test = new Test('t1', [$variant]);
        $subject = new Subject(new RuntimeStorage());

        $engine = new Engine(new SimpleAnalytics());
        $engine->addTest($test, new Percentage(0), new RandomChooser());

        $subject->participate($test, $variant);

        $engine->test($subject);

        $this->assertTrue($subject->participates($test));
    }

    /**
     * @test
     */
    public function subject_can_participate_in_a_test_even_if_the_test_has_no_variants()
    {
        // Arrange
        $test = new Test('t1', [new SimpleVariant('v1')]);

        $engine = new Engine(new SimpleAnalytics());
        $engine->addTest(
            $test,
            $this->alwaysParticipateFilter,
            new RandomChooser(),
            []
        );

        $subject = new Subject(new RuntimeStorage());

        // Act
        $engine->test($subject);

        $result = $subject->participates($test);

        // Assert
        $this->assertTrue($result);
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
        $engine->test($this->createMock(SubjectInterface::class));
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
        $engine->test($this->createMock(SubjectInterface::class));
        $engine->test($this->createMock(SubjectInterface::class));

        // Assert
    }

    /**
     * @test
     */
    public function it_blocks_a_test_correctly()
    {
        // Arrange
        $engine = new Engine(new SimpleAnalytics());
        $subject = new Subject(new RuntimeStorage());

        $test = new Test('t1', [new SimpleVariant('v1')]);

        $engine->addTest($test, new Percentage(0), new RandomChooser());

        // Act
        $engine->test($subject);

        // Assert
        $this->assertTrue($subject->participationIsBlocked($test));
    }

    /**
     * @test
     */
    public function get_analytics()
    {
        // Arrange
        $subject = new Subject(new RuntimeStorage());
        $engine = new Engine(new SimpleAnalytics());

        // Act
        $engine->test($subject);

        // Assert
        $this->assertInstanceOf(AnalyticsInterface::class, $engine->getAnalytics());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function get_analytics_before_engine_was_started()
    {
        // Arrange
        $engine = new Engine(new SimpleAnalytics());

        // Act
        $engine->getAnalytics();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function adding_test_without_variant_throws_exeption()
    {
        // Arrange
        $engine = new Engine(new SimpleAnalytics());

        // Act
        $engine->addTest(new Test('t1'), new Percentage(100), new RandomChooser());
    }
}
