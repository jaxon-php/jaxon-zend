<?php

namespace Jaxon\Zend;

use Zend\Session\Container;

class Session
{
    /**
     * The Zend Framework session
     * 
     * @var object
     */
    protected $xContainer = null;

    public function __construct()
    {
        $this->setContainerName('base');
    }

    /**
     * Set the Zend Framework 
     *
     * @param string        $sKey                The session key
     * 
     * @return void
     */
    public function setContainerName($sName)
    {
        $this->xContainer = new Container($sName);
    }

    /**
     * Save data in the session
     *
     * @param string        $sKey                The session key
     * @param string        $xValue              The session value
     * 
     * @return void
     */
    public function set($sKey, $xValue)
    {
        $this->xContainer->offsetSet($sKey, $xValue);
    }

    /**
     * Check if a session key exists
     *
     * @param string        $sKey                The session key
     * 
     * @return bool             True if the session key exists, else false
     */
    public function has($sKey)
    {
        return $this->xContainer->offsetExists($sKey);
    }

    /**
     * Get data from the session
     *
     * @param string        $sKey                The session key
     * @param string        $xDefault            The default value
     * 
     * @return mixed|$xDefault             The data under the session key, or the $xDefault parameter
     */
    public function get($sKey, $xDefault = null)
    {
        return $this->has($sKey) ? $this->xContainer->offsetGet($sKey) : $xDefault;
    }
}
