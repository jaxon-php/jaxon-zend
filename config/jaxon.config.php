<?php

return array(
    'app' => array(
        'classes' => array(
            array(
                'directory' => rtrim(getcwd(), '/') . '/jaxon/Classes',
                'namespace' => '\\Jaxon\\App',
                // 'separator' => '', // '.' or '_'
                // 'protected' => array(),
            ),
        ),
    ),
    'lib' => array(
        'core' => array(
            'language' => 'en',
            'encoding' => 'UTF-8',
            'request' => array(
                'uri' => '/jaxon',
            ),
            'prefix' => array(
                'class' => '',
            ),
            'debug' => array(
                'on' => false,
                'verbose' => false,
            ),
            'error' => array(
                'handle' => false,
            ),
        ),
        'js' => array(
            'lib' => array(
                // 'uri' => '/jaxon/lib',
            ),
            'app' => array(
                // 'uri' => '',
                // 'dir' => '',
                'extern' => false,
                'minify' => false,
            ),
        ),
    ),
);
