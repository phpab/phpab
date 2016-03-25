<?php

namespace PhpAb\Storage;

use PhpAb\TestInterface;
use RuntimeException;

/**
 * The CookieStorage class holds the value of the test in a cookie so that the test can be executed over
 * multiple sessions.
 */
class CookieStorage implements StorageInterface
{
    /**
     * The name of the cookie.
     *
     * @var string
     */
    private $name;

    /**
     * The lifetime of this cookie in seconds.
     *
     * @var int
     */
    private $lifetime;

    /**
     * The path on the server in which the cookie will be available on.
     *
     * @var string
     */
    private $path;

    /**
     * The domain that the cookie is available to.
     *
     * @var string
     */
    private $domain;

    /**
     * Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client.
     *
     * @var bool
     */
    private $secure;

    /**
     * When true the cookie will be made accessible only through the HTTP protocol.
     *
     * @var bool
     */
    private $httpOnly;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $name The name of the cookie.
     * @param int $lifetime  The lifetime of this cookie in seconds.
     * @param null|string $path The path on the server in which the cookie will be available on.
     * @param null|string $domain The domain that the cookie is available to.
     * @param bool $secure When true the cookie will only be sent over a secure HTTPS connection from the client.
     * @param bool $httpOnly When true the cookie will be made accessible only through the HTTP protocol.
     */
    public function __construct($name, $lifetime, $path = null, $domain = null, $secure = false, $httpOnly = false)
    {
        $this->name = $name;
        $this->lifetime = $lifetime;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }

    /**
     * Builds the name of the cookie for the given test.
     *
     * @param TestInterface $test The test to build the name of the cookie for.
     * @return string
     */
    private function getCookieName(TestInterface $test)
    {
        $replaced = preg_replace('/[^a-z0-9]+/i', '_', $this->name . '_' . $test->getName());

        return strtolower($replaced);
    }

    /**
     * @inheritdoc
     */
    public function clear(TestInterface $test)
    {
        if (headers_sent()) {
            throw new RuntimeException('The headers are already sent. Cannot clear cookie.');
        }

        $cookieName = $this->getCookieName($test);

        setcookie($cookieName, '', time() - 1, $this->path, $this->domain, $this->secure, $this->httpOnly);
    }

    /**
     * @inheritdoc
     */
    public function read(TestInterface $test)
    {
        $cookieName = $this->getCookieName($test);

        return isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : null;
    }

    /**
     * @inheritdoc
     */
    public function write(TestInterface $test, $choice)
    {
        if (headers_sent()) {
            throw new RuntimeException('The headers are already sent. Cannot set cookie.');
        }

        $cookieName = $this->getCookieName($test);

        setcookie(
            $cookieName,
            $choice,
            time() + $this->lifetime,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly
        );
    }
}
