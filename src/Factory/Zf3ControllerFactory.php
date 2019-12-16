<?php

namespace Jaxon\Zend\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class Zf3ControllerFactory implements FactoryInterface
{
    /**
     * Create a Jaxon Controller
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Get and configure the Jaxon plugin
        $jaxonPlugin = $container->get('JaxonPlugin');
        $jaxonPlugin->setContainer($container);
        // Create the Controller, passing the JaxonPlugin as parameter
        return new $requestedName($jaxonPlugin);
    }
}
