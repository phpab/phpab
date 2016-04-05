<?php

namespace PhpAb\Storage;

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
     * Reset global cookies array and disable 
     * global function mocks
     */
    protected function tearDown()
    {
        parent::tearDown();
        $_COOKIE = [];
        \phpmock\Mock::disableAll();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorExceptionNameNotString()
    {
        new Cookie(123);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorExceptionNameEmpty()
    {
        new Cookie('');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorExceptionTtlNotInt()
    {
        new Cookie('chars', 'bar');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testHasException()
    {
        $cookie = new Cookie('chars');
        $cookie->has([123]);
    }

    public function testParseExistingCookie()
    {
        $cookie = new Cookie('chars');
        $this->assertSame([], $cookie->all());

        $_COOKIE['chars'] = 'this is not a proper serialized array';
        $cookie = new Cookie('chars');
        $this->assertSame([], $cookie->all());

        $_COOKIE['chars'] = '{"walter": "white","bernard"}';
        $cookie = new Cookie('chars');
        $this->assertSame([], $cookie->all());
    }

    public function testHas()
    {
        $_COOKIE['chars'] = json_encode([
            'walter' => 'white',
            'bernard' => 'black',
        ]);
        $cookie = new Cookie('chars');
        $this->assertTrue($cookie->has('walter'));
        $this->assertFalse($cookie->has('foo'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetException()
    {
        $cookie = new Cookie('chars');
        $cookie->get([123]);
    }

    public function testGet()
    {
        $_COOKIE['chars'] = json_encode([
            'walter' => 'white',
            'bernard' => 'black',
        ]);

        $cookie = new Cookie('chars');
        $this->assertSame('white', $cookie->get('walter'));
        $this->assertNull($cookie->get('foo'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetExceptionIdentifierNotString()
    {
        $cookie = new Cookie('chars');
        $cookie->set(123, 'black');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetExceptionParticipationEmpty()
    {
        $cookie = new Cookie('chars');
        $cookie->set('walter', '');
    }

    public function testSet()
    {

        // Mock global functions
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("headers_sent")
                ->setFunctionProvider(new FixedValueFunction(false));
        $headersSentMock = $builder->build();


        $builder->setNamespace(__NAMESPACE__)
                ->setName("setcookie")
                ->setFunctionProvider(new FixedValueFunction(true));
        $setcookieMock = $builder->build();

        $headersSentMock->enable();
        $setcookieMock->enable();

        $_COOKIE['chars'] = json_encode([
            'walter' => 'white',
            'bernard' => 'black',
        ]);

        $cookie = new Cookie('chars');
        $cookie->set('walter', 'black');
        $this->assertSame('black', $cookie->get('walter'));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetExceptionHeadersSent()
    {
        $cookie = new Cookie('chars');
        $cookie->set('walter', 'black');
    }

    public function testAll()
    {
        $values = [
            'walter' => 'white',
            'bernard' => 'black',
        ];
        $_COOKIE['chars'] = json_encode($values);
        $cookie = new Cookie('chars');
        $this->assertSame($values, $cookie->all());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRemoveExceptionIdentifierNotString()
    {
        $cookie = new Cookie('chars');
        $cookie->remove(123);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testRemoveExceptionHeadersSent()
    {
        $cookie = new Cookie('chars');
        $cookie->remove('foo');
    }

    public function testRemove()
    {

        // Mock global functions
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("headers_sent")
                ->setFunctionProvider(new FixedValueFunction(false));
        $headersSentMock = $builder->build();

        $builder->setNamespace(__NAMESPACE__)
                ->setName("setcookie")
                ->setFunctionProvider(new FixedValueFunction(true));
        $setcookieMock = $builder->build();

        $headersSentMock->enable();
        $setcookieMock->enable();

        $_COOKIE['chars'] = json_encode([
            'walter' => 'white',
            'bernard' => 'black',
        ]);

        $cookie = new Cookie('chars');
        $this->assertNull($cookie->remove('django'));
        $this->assertSame('white', $cookie->remove('walter'));
        $this->assertSame(['bernard' => 'black'], $cookie->all());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testClearExceptionHeadersSent()
    {
        $cookie = new Cookie('chars');
        $cookie->clear();
    }

    public function testClear()
    {

        // Mock global functions
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("headers_sent")
                ->setFunctionProvider(new FixedValueFunction(false));
        $headersSentMock = $builder->build();

        $builder->setNamespace(__NAMESPACE__)
                ->setName("setcookie")
                ->setFunctionProvider(new FixedValueFunction(true));
        $setcookieMock = $builder->build();

        $headersSentMock->enable();
        $setcookieMock->enable();

        $values = [
            'walter' => 'white',
            'bernard' => 'black',
        ];

        $_COOKIE['chars'] = json_encode($values);
        $cookie = new Cookie('chars');
        $this->assertSame($values, $cookie->clear());
        $this->assertEmpty($cookie->all());
    }
}
