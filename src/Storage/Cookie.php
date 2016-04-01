<?php

namespace PhpAb\Storage;

use InvalidArgumentException;
use RuntimeException;

/**
 * Stores the participation state of the user in a session.
 */
class Cookie implements StorageInterface {

    /**
     * @var string Name of cookie
     */
    protected $cookiename;

    /**
     *
     * @var int Cookie's time to live in seconds
     */
    protected $ttl;

    /**
     *
     * @var mixed null|array Array of which will be saved in cookie
     */
    protected $cookievalues;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $cookiename   The name the cookie.
     * @param int   $ttl           How long should the cookie last in browser. Default 5 years
     *                              Setting a negative number will make cookie expire after current session
     */
    public function __construct($cookiename, $ttl = 157766400) {

        // We cannot typehint for primitive types yet so therefor we check if the cookie name is a (valid) string.
        if (!is_string($cookiename) || empty($cookiename)) {
            throw new InvalidArgumentException('The cookie name is invalid.');
        }

        if (!is_int($ttl)) {
            throw new InvalidArgumentException('The cookie ttl parameter should be a integer.');
        }

        $this->cookiename = $cookiename;

        $this->ttl = $ttl;
    }

    /**
     * Parses any previous cookie and stores is internally
     * @return void
     */
    protected function parseExistingCookie() {

        if (is_array($this->cookievalues)) {
            return;
        }

        if (empty($_COOKIE) || !isset($_COOKIE[$this->cookiename])) {
            $this->cookievalues = [];
            return;
        }

        $deserialized_cookie = json_decode($_COOKIE[$this->cookiename], true);

        if (is_null($deserialized_cookie)) {
            $this->cookievalues = [];
            return;
        }

        $this->cookievalues = $deserialized_cookie;
    }

    /**
     * Saves cookie with serialized test values
     * @return bool
     */
    protected function saveCookie() {

        $this->parseExistingCookie();
        return setcookie($this->cookiename, json_encode($this->cookievalues), time() + $this->ttl, '/');
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function has($identifier) {

        if (!is_string($identifier) || empty($identifier)) {
            throw new InvalidArgumentException('Test identifier is invalid.');
        }

        $this->parseExistingCookie();

        return isset($this->cookievalues[$identifier]);
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function get($identifier) {

        if (!is_string($identifier) || empty($identifier)) {
            throw new InvalidArgumentException('Test identifier is invalid.');
        }

        $this->parseExistingCookie();

        if (!$this->has($identifier)) {
            return null;
        }

        return $this->cookievalues[$identifier];
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function set($identifier, $participation) {

        if (!is_string($identifier) || empty($identifier)) {
            throw new InvalidArgumentException('Test identifier is invalid.');
        }

        if (empty($participation)) {
            throw new InvalidArgumentException('Participation name is invalid.');
        }

        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $this->parseExistingCookie();

        $this->cookievalues[$identifier] = $participation;

        return $this->saveCookie();
    }

    /**
     * @inheritDoc
     */
    public function all() {

        $this->parseExistingCookie();
        return $this->cookievalues;
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function remove($identifier) {

        if (!is_string($identifier) || empty($identifier)) {
            throw new InvalidArgumentException('Test identifier is invalid.');
        }

        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $this->parseExistingCookie();

        $value = $this->get($identifier);

        if (is_null($value)) {
            return null;
        }

        unset($this->cookievalues[$identifier]);

        $this->saveCookie();

        return $value;
    }

    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function clear() {

        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $this->parseExistingCookie();
        $values = $this->cookievalues;
        $this->cookievalues = [];
        $this->saveCookie();

        return $values;
    }

}
