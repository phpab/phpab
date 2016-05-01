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
use Webmozart\Assert\Assert;

/**
 * Stores the participation state of the user in a session.
 *
 * @package PhpAb
 */
class Cookie implements StorageInterface
{
    /**
     * The name of cookie.
     *
     * @var string
     */
    protected $cookieName;

    /**
     * The cookie's time to live in seconds
     *
     * @var int
     */
    protected $ttl;

    /**
     * The array of which will be saved in cookie.
     *
     * @var null|array
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
        Assert::string($cookieName, 'The cookie name is invalid.');
        Assert::notEmpty($cookieName, 'The cookie name is invalid.');
        Assert::integer($ttl, 'The cookie ttl parameter should be a integer.');

        $this->cookieName = $cookieName;

        $this->ttl = $ttl;
    }

    /**
     * Parses any previous cookie and stores it internally
     */
    protected function parseExistingCookie()
    {
        if (is_array($this->cookieValues)) {
            return;
        }

        $cookiesContent = filter_input_array(INPUT_COOKIE);

        if (empty($cookiesContent) || !isset($cookiesContent[$this->cookieName])) {
            $this->cookieValues = [];
            return;
        }

        $deserializedCookie = json_decode($cookiesContent[$this->cookieName], true);

        if (is_null($deserializedCookie)) {
            $this->cookieValues = [];
            return;
        }

        $this->cookieValues = $deserializedCookie;
    }

    /**
     * Saves cookie with serialized test values
     *
     * @return bool
     */
    protected function saveCookie()
    {
        $this->parseExistingCookie();

        return setcookie($this->cookieName, json_encode($this->cookieValues), time() + $this->ttl, '/');
    }

    /**
     * {@inheritDoc}
     *
     * @param string $identifier The tests identifier
     */
    public function has($identifier)
    {
        Assert::string($identifier, 'Test identifier is invalid.');
        Assert::notEmpty($identifier, 'Test identifier is invalid.');
        
        $this->parseExistingCookie();

        return isset($this->cookieValues[$identifier]);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $identifier The tests identifier name
     */
    public function get($identifier)
    {
        if (!$this->has($identifier)) {
            return null;
        }

        return $this->cookieValues[$identifier];
    }

    /**
     * {@inheritDoc}
     *
     * @param string $identifier The tests identifier
     * @param mixed  $participation The participated variant
     * @throws RuntimeException
     *
     * @return bool
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
     * {@inheritDoc}
     */
    public function all()
    {
        $this->parseExistingCookie();

        return $this->cookieValues;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $identifier
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
     * {@inheritDoc}
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
