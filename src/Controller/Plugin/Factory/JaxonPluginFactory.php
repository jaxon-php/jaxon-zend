<?php

namespace Jaxon\Zend\Controller\Plugin\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Jaxon\Zend\Controller\Plugin\Jaxon;

class JaxonPluginFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Jaxon($serviceLocator->get('ViewRenderer'));
    }
}
