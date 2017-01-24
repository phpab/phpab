<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb;

use PhpAb\Storage\Storage;
use PhpAb\Storage\Adapter\Runtime;
use PhpAb\Test\Test;
use PhpAb\Variant\SimpleVariant;
use PHPUnit_Framework_TestCase;

class SubjectTest extends PHPUnit_Framework_TestCase
{
    private $storage;

    public function setUp()
    {
        $this->storage = new Storage(new Runtime());
    }

    public function testCheckParticipation()
    {
        // Arrange
        $subject = new Subject($this->storage);

        // Act
        $result = $subject->participates('foo');

        // Assert
        $this->assertFalse($result);
    }

    public function testCheckParticipatesTestSuccess()
    {
        // Arrange
        $subject = new Subject($this->storage);
        $subject->participate('foo', 'bar');

        // Act
        $result = $subject->participates('foo');

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
        $subject->participate(new Test('foo'), null);

        // Act
        $result = $subject->participates('foo');

        // Assert
        $this->assertTrue($result);
    }

    public function testCheckParticipatesTestVariantObjectSuccess()
    {
        // Arrange
        $subject = new Subject($this->storage);
        $subject->participate(new Test('foo'), new SimpleVariant('bar'));

        // Act
        $result = $subject->participates('foo', 'bar');

        // Assert
        $this->assertTrue($result);
    }

    public function testCheckParticipatesVariantSuccess()
    {
        // Arrange
        $subject = new Subject($this->storage);
        $subject->participate('foo', 'bar');

        // Act
        $result = $subject->participates('foo', 'bar');

        // Assert
        $this->assertTrue($result);
    }

    public function testCheckParticipatesVariantFail()
    {
        // Arrange
        $subject = new Subject($this->storage);
        $subject->participate('foo', 'yolo');

        // Act
        $result = $subject->participates('foo', 'bar');

        // Assert
        $this->assertFalse($result);
    }

    // More to come

    public function tearDown()
    {
        $this->storage->clear();
    }
}
