<?php

namespace Jaxon\Zend;

use Jaxon\App\Session\SessionInterface;
use Zend\Session\Container;

class Session implements SessionInterface
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
     * Get the current session id
     *
     * @return string           The session id
     */
    public function getId(): string
    {
        return $this->xContainer->getManager()->getId();
    }

    /**
     * Generate a new session id
     *
     * @param bool          $bDeleteData         Whether to delete data from the previous session
     *
     * @return void
     */
    public function newId($bDeleteData = false)
    {
        $this->xContainer->getManager()->regenerateId($bDeleteData);
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
    public function has($sKey): bool
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

    /**
     * Get all data in the session
     *
     * @return array             An array of all data in the session
     */
    public function all(): array
    {
        return $this->xContainer->getArrayCopy();
    }

    /**
     * Delete a session key and its data
     *
     * @param string        $sKey                The session key
     *
     * @return void
     */
    public function delete($sKey)
    {
        $this->xContainer->offsetUnset($sKey);
    }

    /**
     * Delete all data in the session
     *
     * @return void
     */
    public function clear()
    {
        $this->xContainer->exchangeArray([]);
    }
}
