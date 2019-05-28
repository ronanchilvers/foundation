<?php

namespace Ronanchilvers\Foundation\Traits;

/**
 * Trait for objects which have options
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
trait Optionable
{
    /**
     * @var array
     */
    private $defaults = [];

    /**
     * @var array
     */
    private $options;

    /**
     * Set the defaults for this object
     *
     * @param array $defaults
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;
    }

    /**
     * Set the options for this object
     *
     * @param array $options
     * @return static
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Set an option by key
     *
     * @param string $key
     * @param mixed $value
     * @return static
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function setOption(string $key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * Get an option with a default
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function getOption(string $key, $default = null)
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }
        if (isset($this->defaults[$key])) {
            return $this->defaults[$key];
        }

        return $default;
    }
}
