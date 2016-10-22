<?php

namespace Jaxon\Zend\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Jaxon\Zend\Controller\JaxonController;

class JaxonControllerFactory implements FactoryInterface
{
    /**
     * Create a Jaxon Controller
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Service Manager
        $sm = $serviceLocator->getServiceLocator();
        // Controller name
        $routeMatch = $sm->get('Application')->getMvcEvent()->getRouteMatch();
        $controllerName = $routeMatch->getParam('controller');
        // Controller class
        $controllerClass = $controllerName;
        if(substr($controllerName, 0, 1) != '\\')
        {
            $controllerClass = '\\' . $controllerName;
        }
        if(substr($controllerName, -10) != 'Controller')
        {
            $controllerClass .= 'Controller';
        }
        // Create the Jaxon Controller, passing the JaxonPlugin as parameter
        return new $controllerClass($sm->get('JaxonPlugin'));
    }
}
