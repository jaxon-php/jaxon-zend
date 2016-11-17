<?php

namespace Jaxon\Zend\Controller\Plugin\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Jaxon\Zend\Controller\Plugin\JaxonPlugin;

class JaxonPluginFactory implements FactoryInterface
{
    /**
     * Create the JaxonPlugin service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return \Jaxon\Zend\Controller\JaxonController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Create the JaxonPlugin, with the ViewRenderer as parameter
        return new JaxonPlugin($serviceLocator->get('ViewRenderer'));
    }
}
