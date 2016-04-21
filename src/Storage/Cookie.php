<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage;

use InvalidArgumentException;
use RuntimeException;

/**
 * Stores the participation state of the user in a session.
 */
class Cookie implements StorageInterface
{

    /**
     * @var string Name of cookie
     */
    protected $cookieName;

    /**
     * @var int Cookie's time to live in seconds
     */
    protected $ttl;

    /**
     * @var mixed null|array Array of which will be saved in cookie
     */
    protected $cookieValues;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $cookieName   The name the cookie.
     * @param int   $ttl           How long should the cookie last in browser. Default 5 years
     *                             Setting a negative number will make cookie expire after current session
     * @throws InvalidArgumentException
     */
    public function __construct($cookieName, $ttl = 157766400)
    {

        // We cannot typehint for primitive types yet so therefore we check if the cookie name is a (valid) string.
        if (!is_string($cookieName) || empty($cookieName)) {
            throw new InvalidArgumentException('The cookie name is invalid.');
        }

        if (!is_int($ttl)) {
            throw new InvalidArgumentException('The cookie ttl parameter should be a integer.');
        }

        $this->cookieName = $cookieName;

        $this->ttl = $ttl;
    }

    /**
     * Parses any previous cookie and stores it internally
     * @return void
     */
    protected function parseExistingCookie()
    {
        if (is_array($this->cookieValues)) {
            return;
        }

        if (empty($_COOKIE) || !isset($_COOKIE[$this->cookieName])) {
            $this->cookieValues = [];
            return;
        }

        $deserializedCookie = json_decode($_COOKIE[$this->cookieName], true);

        if (is_null($deserializedCookie)) {
            $this->cookieValues = [];
            return;
        }

        $this->cookieValues = $deserializedCookie;
    }

    /**
     * Saves cookie with serialized test values
     * @return bool
     */
    protected function saveCookie()
    {
        $this->parseExistingCookie();

        return setcookie($this->cookieName, json_encode($this->cookieValues), time() + $this->ttl, '/');
    }

    /**
     * @inheritDoc
     */
    public function has($identifier)
    {
        if (!is_string($identifier) || $identifier === '') {
            throw new InvalidArgumentException('Test identifier is invalid.');
        }

        $this->parseExistingCookie();

        return isset($this->cookieValues[$identifier]);
    }

    /**
     * @inheritDoc
     */
    public function get($identifier)
    {
        if (!$this->has($identifier)) {
            return null;
        }

        return $this->cookieValues[$identifier];
    }

    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function set($identifier, $participation)
    {
        $this->has($identifier);

        if ('' === $participation) {
            throw new InvalidArgumentException('Participation name is invalid.');
        }

        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $this->parseExistingCookie();

        $this->cookieValues[$identifier] = $participation;

        return $this->saveCookie();
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        $this->parseExistingCookie();

        return $this->cookieValues;
    }

    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function remove($identifier)
    {
        $this->has($identifier);

        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $this->parseExistingCookie();

        $value = $this->get($identifier);

        if (is_null($value)) {
            return null;
        }

        unset($this->cookieValues[$identifier]);

        $this->saveCookie();

        return $value;
    }

    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function clear()
    {
        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $this->parseExistingCookie();
        $values = $this->cookieValues;
        $this->cookieValues = [];
        $this->saveCookie();

        return $values;
    }
}
