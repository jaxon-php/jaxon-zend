<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'JaxonController' => 'Jaxon\Zend\Controller\JaxonController',
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
                        'controller' => 'JaxonController',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
);
