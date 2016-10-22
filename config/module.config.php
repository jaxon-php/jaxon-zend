<?php

return array(
    'controllers' => array(
        'factories' => array(
            'Jaxon\Zend\Controller\Jaxon' => Jaxon\Zend\Factory\JaxonControllerFactory::class,
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'JaxonPlugin' => 'Jaxon\Zend\Controller\Plugin\Factory\JaxonPluginFactory',
        )
    ),
    'router' => array(
        'routes' => array(
            // Route to the Jaxon request processor
            'jaxon' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/jaxon',
                    'defaults' => array(
                        'controller' => 'Jaxon\Zend\Controller\Jaxon',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
);
