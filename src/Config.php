<?php

namespace Ronanchilvers\Foundation;

use ArrayAccess;
use Iterator;

/**
 * Simple class for managing a configuration array
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Config implements ArrayAccess, Iterator
{
    /**
     * @var array
     */
    protected $data;

    /**
     * Class constructor
     *
     * @param array $data
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Set a value into the config array supporting dot notation
     *
     * @param string $key
     * @param mixed $value
     * @throws RuntimeException If the key cannot be set or clashes with an existing key
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function set(string $key, $value)
    {
        return $this->offsetSet($key, $value);
    }

    /**
     * Does this config have a given key?
     *
     * @param string $key
     * @return boolean
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function has(string $key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Get a configuration value using dot notation with an optional default
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function get(string $key, $default = null)
    {
        $value = $this->offsetGet($key);
        if (!is_null($value)) {
            return $value;
        }

        return $default;
    }

    /**
     * Get a given key, throwing an exception if it is not found
     *
     * @param string $key
     * @param string $message The exception message to throw
     * @return mixed
     * @throws RuntimeException If the key is not found
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getOrThrow(string $key, string $message = null)
    {
        $value = $this->offsetGet($key);
        if (!is_null($value)) {
            return $value;
        }
        if (is_null($message)) {
            $message = "Key {$key} not found";
        }
        throw new RuntimeException($message);
    }

    /**
     * Get all the keys for this config object
     *
     * @return array
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Merge another config object into this one
     *
     * @param Ronanchilvers\Foundation\Config $config
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function merge(Config $config)
    {
        $this->data = array_replace_recursive(
            $this->data,
            $config->data
        );
    }

    /** START Iterator compliance **/

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function next()
    {
        next($this->data);
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function valid()
    {
        return key($this->data) !== null;
    }

    /** END Iterator compliance **/

    /** START ArrayAccess compliance **/

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function offsetExists($offset)
    {
        if (false === strpos($offset, '.')) {
            return isset($this->data[$offset]);
        }
        $bits = explode('.', $offset);
        $data = $this->data;
        foreach ($bits as $bit) {
            if (isset($data[$bit])) {
                if (is_array($data[$bit])) {
                    $data = $data[$bit];
                    continue;
                }

                return true;
            }
            break;
        }

        return false;
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function offsetGet($offset)
    {
        if (false === strpos($offset, '.')) {
            if (isset($this->data[$offset])) {
                return $this->data[$offset];
            }

            return null;
        }

        $bits = explode('.', $offset);
        $data = $this->data;
        foreach ($bits as $bit) {
            if (isset($data[$bit])) {
                if (is_array($data[$bit])) {
                    $data = $data[$bit];
                    continue;
                }

                return $data[$bit];
            }

            return null;
        }
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function offsetSet($offset, $value)
    {
        if (false === strpos($offset, '.')) {
            $this->data[$offset] = $value;

            return $this;
        }
        $bits = explode('.', $offset);
        $final = array_pop($bits);
        $data = &$this->data;
        foreach ($bits as $bit) {
            if (!isset($data[$bit])) {
                $data[$bit] = [];
            } elseif (!is_array($data[$bit])) {
                throw new RuntimeException("Unable to overwrite existing data for key {$offset}");
            }
            $data = &$data[$bit];
        }
        $data[$final] = $value;

        return $this;
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /** END ArrayAccess compliance **/
}
