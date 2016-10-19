<?php

namespace Jaxon\Zend;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /*public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'jaxon' => 'Jaxon\Zend\Controller\Plugin\Factory\JaxonPluginFactory',
            ),
        );
    }*/
}
