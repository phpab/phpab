<?php

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage\Adapter;

use PHPUnit_Framework_TestCase;
use phpmock\MockBuilder;
use phpmock\Mock;
use phpmock\functions\FixedValueFunction;

/**
 * During the execution of some of these tests,
 * global functions might be overwritten
 */
class CookieTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array Default test results used for test suite
     */
    private $testValues = [
        'walter' => 'white',
        'bernard' => 'black'
    ];
    private $mockHeadersSent;
    private $mockSetCookies;
    private $mockFilterInputArray;

    /**
     * Reset global cookies array and disable
     * global function mocks
     */
    protected function tearDown()
    {
        parent::tearDown();
        Mock::disableAll();
    }

    private function setMockHeadersSentReturnValue($returnValue)
    {
        if ($this->mockHeadersSent) {
            $this->mockHeadersSent->disable();
        }

        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
            ->setName("headers_sent")
            ->setFunctionProvider(new FixedValueFunction($returnValue));
        $this->mockHeadersSent = $builder->build();
        $this->mockHeadersSent->enable();
    }

    private function setSetCookiesReturnValue($returnValue)
    {
        if ($this->mockSetCookies) {
            $this->mockSetCookies->disable();
        }

        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
            ->setName("setcookie")
            ->setFunctionProvider(new FixedValueFunction($returnValue));
        $this->mockSetCookies = $builder->build();
        $this->mockSetCookies->enable();
    }

    private function setFilterInputArrayReturnValue($returnValue)
    {
        if ($this->mockFilterInputArray) {
            $this->mockFilterInputArray->disable();
        }

        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
            ->setName("filter_input_array")
            ->setFunctionProvider(new FixedValueFunction($returnValue));

        $this->mockFilterInputArray = $builder->build();
        $this->mockFilterInputArray->enable();
    }

    public function setUp()
    {
        parent::setUp();

        $this->setMockHeadersSentReturnValue(false);
        $this->setSetCookiesReturnValue(true);
        $this->setFilterInputArrayReturnValue(['chars' => json_encode($this->testValues)]);
    }

    /**
     * Testing that constructor's first argument can only be non empty strings
     *
     * @expectedException InvalidArgumentException
     */
    public function testConstructorExceptionNameNotString()
    {
        // Arrange
        new Cookie(123);

        // Act

        // Assert
    }

    /**
     * Testing that constructor's first argument can only be non empty strings
     *
     * @expectedException InvalidArgumentException
     */
    public function testConstructorExceptionNameEmpty()
    {
        // Arrange
        new Cookie('');

        // Act

        // Assert
    }

    /**
     * Testing that constructor's second argument can only be an integer
     *
     * @expectedException InvalidArgumentException
     */
    public function testConstructorExceptionTtlNotInt()
    {
        // Arrange
        new Cookie('chars', 'bar');

        // Act

        // Assert
    }

    /**
     * Testing that has() accepts only non empty strings
     *
     * @expectedException InvalidArgumentException
     */
    public function testHasException()
    {
        // Arrange
        $cookie = new Cookie('chars');

        // Act
        $cookie->has([123]);

        // Assert
    }

    /**
     * Test that parseExistingCookie() will set an empty array if cookie is empty
     */
    public function testParseExistingCookieEmpty()
    {
        // Arrange
        $this->setFilterInputArrayReturnValue([]);
        $cookie = new Cookie('chars');

        // Act
        $values = $cookie->all();

        // Assert
        $this->assertSame([], $values);
    }

    /**
     * Test that parseExistingCookie() will set an empty array if cookie is not an array
     */
    public function testParseExistingCookieNotArray()
    {
        // Arrange
        $this->setFilterInputArrayReturnValue(['chars' => 'this is not a proper serialized array']);
        $cookie = new Cookie('chars');

        // Act
        $values = $cookie->all();

        // Assert
        $this->assertSame([], $values);
    }

    /**
     * Test that parseExistingCookie() will set an empty array if cookie is a wrong json object
     */
    public function testParseExistingCookieNonValidJson()
    {
        // Arrange
        $this->setFilterInputArrayReturnValue(['chars' => '{"walter": "white","bernard"}']);
        $cookie = new Cookie('chars');

        // Act
        $values = $cookie->all();

        // Assert
        $this->assertSame([], $values);
    }

    /**
     * Test values returnd by has()
     */
    public function testHas()
    {
        // Arrange
        $cookie = new Cookie('chars');

        // Act
        $hasValues1 = $cookie->has('walter');
        $hasValue2 = $cookie->has('foo');

        // Assert
        $this->assertTrue($hasValues1);
        $this->assertFalse($hasValue2);
    }

    /**
     * Testing that get() accepts only non empty strings
     *
     * @expectedException InvalidArgumentException
     */
    public function testGetException()
    {
        // Arrange
        $cookie = new Cookie('chars');

        // Act
        $cookie->get([123]);

        // Assert
    }

    /**
     * Testing values returned by get()
     */
    public function testGet()
    {
        // Arrange
        $cookie = new Cookie('chars');

        // Act
        $getVal1 = $cookie->get('walter');
        $getVal2 = $cookie->get('foo');

        // Assert
        $this->assertSame('white', $getVal1);
        $this->assertNull($getVal2);
    }

    /**
     * Testing that set()'s first argument can only be a non empty string
     *
     * @expectedException InvalidArgumentException
     */
    public function testSetExceptionIdentifierNotString()
    {
        // Arrange
        $cookie = new Cookie('chars');

        // Act
        $cookie->set(123, 'black');

        // Assert
    }

    /**
     * Testing that set()'s second argument can only be a non empty string
     *
     * @expectedException InvalidArgumentException
     */
    public function testSetExceptionParticipationEmpty()
    {
        // Arrange
        $cookie = new Cookie('chars');

        // Act
        $cookie->set('walter', '');

        // Assert
    }

    /**
     * Testing that values passed to set() are the ones returned by get()
     */
    public function testSet()
    {
        // Arrange
        $cookie = new Cookie('chars');

        // Act
        $cookie->set('walter', 'black');

        // Assert
        $this->assertSame('black', $cookie->get('walter'));
    }

    /**
     * Test that set() will throw exception of if headers are already sent
     * @expectedException RuntimeException
     */
    public function testSetExceptionHeadersSent()
    {
        // Arrange
        $this->setMockHeadersSentReturnValue(true);
        $cookie = new Cookie('chars');

        // Act
        $cookie->set('walter', 'black');

        // Assert
    }

    /**
     * Test that all() returns all test values
     */
    public function testAll()
    {
        // Arrange
        $cookie = new Cookie('chars');

        // Act
        // Arrange
        $this->assertSame($this->testValues, $cookie->all());
    }

    /**
     * Test that remove() only accepts non empty strings
     *
     * @expectedException InvalidArgumentException
     */
    public function testRemoveExceptionIdentifierNotString()
    {
        // Arrange
        $cookie = new Cookie('chars');

        // Act
        $cookie->remove(123);
    }

    /**
     * Test that remove will throw exception if headers are already sent
     *
     * @expectedException RuntimeException
     */
    public function testRemoveExceptionHeadersSent()
    {
        // Arrange
        $this->setMockHeadersSentReturnValue(true);
        $cookie = new Cookie('chars');

        // Act
        $cookie->remove('foo');
    }

    /**
     * Tet that remove() removes tests values from storage
     */
    public function testRemove()
    {

        // Arrange
        $cookie = new Cookie('chars');

        // Act
        //
        // Assert
        $this->assertNull($cookie->remove('django'));
        $this->assertSame('white', $cookie->remove('walter'));
        $this->assertSame(['bernard' => 'black'], $cookie->all());
    }

    /**
     * Test that clear() throws exception if headers are already sent
     * @expectedException RuntimeException
     */
    public function testClearExceptionHeadersSent()
    {
        // Arrange
        $this->setMockHeadersSentReturnValue(true);
        $cookie = new Cookie('chars');

        // Act
        $cookie->clear();

        // Assert
    }

    /**
     * Testing that clear() resets values of storage
     */
    public function testClear()
    {
        // Arrange
        $cookie = new Cookie('chars');

        // Act
        $cookie->clear();

        // Assert
        $this->assertEmpty($cookie->all());
    }
}
