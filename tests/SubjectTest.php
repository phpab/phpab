<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb;

use PhpAb\Storage\RuntimeStorage;
use PhpAb\Test\Test;
use PhpAb\Variant\SimpleVariant;
use PHPUnit_Framework_TestCase;

class SubjectTest extends PHPUnit_Framework_TestCase
{
    private $storage;

    public function setUp()
    {
        $this->storage = new RuntimeStorage();
    }

    public function testCheckParticipation()
    {
        // Arrange
        $subject = new Subject($this->storage);

        $test = new Test('foo');

        // Act
        $result = $subject->participates($test);

        // Assert
        $this->assertFalse($result);
    }

    public function testCheckParticipatesTestSuccess()
    {
        // Arrange
        $subject = new Subject($this->storage);
        $test = new Test('foo', [
            new SimpleVariant('bar')
        ]);
        $subject->participate($test, $test->getVariant('bar'));

        // Act
        $result = $subject->participates($test);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function check_if_subject_participates_in_a_chosen_test()
    {
        // Arrange
        $subject = new Subject($this->storage);
        $test = new Test('foo');
        $subject->participate($test, new SimpleVariant('foo'));

        // Act
        $result = $subject->participates($test);

        // Assert
        $this->assertTrue($result);
    }

    public function testCheckParticipatesTestVariantSuccess()
    {
        // Arrange
        $subject = new Subject($this->storage);
        $test = new Test('foo');
        $variant = new SimpleVariant('bar');

        $subject->participate($test, $variant);

        // Act
        $result = $subject->participates($test, $variant);

        // Assert
        $this->assertTrue($result);
    }

    public function testCheckParticipatesVariantFail()
    {
        // Arrange
        $subject = new Subject($this->storage);
        $test = new Test('foo');
        $subject->participate($test, new SimpleVariant('yolo'));

        // Act
        $result = $subject->participates($test, new SimpleVariant('bar'));

        // Assert
        $this->assertFalse($result);
    }

    // More to come

    public function tearDown()
    {
        $this->storage->clear();
    }
}
