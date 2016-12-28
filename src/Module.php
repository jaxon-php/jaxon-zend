<?php

namespace Jaxon\Zend;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    /**
     * Get the plugin config
     *
     * @return array
     */
    public function getConfig()
    {
        return array();
    }
}
