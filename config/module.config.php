<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'JaxonController' => 'Jaxon\Zend\Controller\JaxonController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'jaxon' => 'Jaxon\Zend\Controller\Plugin\Jaxon',
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
