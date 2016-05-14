<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage\Adapter;

use InvalidArgumentException;
use RuntimeException;
use Webmozart\Assert\Assert;

/**
 * Cookie Storage Adapter records the participation state of the user in a cookie.
 *
 * @package PhpAb
 */
class Cookie implements AdapterInterface
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
    protected $data;

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
        if (is_array($this->data)) {
            return;
        }

        $cookiesContent = filter_input_array(INPUT_COOKIE);

        if (empty($cookiesContent) || !array_key_exists($this->cookieName, $cookiesContent)) {
            $this->data = [];
            return;
        }

        $deserializedCookie = json_decode($cookiesContent[$this->cookieName], true);

        if (is_null($deserializedCookie)) {
            $this->data = [];
            return;
        }

        $this->data = $deserializedCookie;
    }

    /**
     * {@inheritDoc}
     */
    protected function saveCookie()
    {
        $this->parseExistingCookie();

        return setcookie($this->cookieName, json_encode($this->data), time() + $this->ttl, '/');
    }

    /**
     * {@inheritDoc}
     */
    public function has($identifier)
    {
        Assert::string($identifier, 'Test identifier is invalid.');
        Assert::notEmpty($identifier, 'Test identifier is invalid.');

        $this->parseExistingCookie();

        return array_key_exists($identifier, $this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function get($identifier)
    {
        if (!$this->has($identifier)) {
            return null;
        }

        return $this->data[$identifier];
    }

    /**
     * {@inheritDoc}
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

        $this->data[$identifier] = $participation;

        return $this->saveCookie();
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        $this->parseExistingCookie();

        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($identifier)
    {
        $this->has($identifier);

        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $value = $this->get($identifier);

        if (is_null($value)) {
            return null;
        }

        unset($this->data[$identifier]);

        $this->saveCookie();

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $this->parseExistingCookie();
        $values = $this->data;
        $this->data = [];
        $this->saveCookie();

        return $values;
    }
}
