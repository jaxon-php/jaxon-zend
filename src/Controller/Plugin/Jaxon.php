<?php

namespace Jaxon\Zend\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Jaxon extends AbstractPlugin
{
    use \Jaxon\Framework\JaxonTrait;

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Setup the Jaxon module.
     *
     * @return void
     */
    public function setup()
    {
        // This function should be called only once
        if(($this->setupCalled))
        {
            return;
        }
        $this->setupCalled = true;

        $this->jaxon = jaxon();
        $this->response = new \Jaxon\Zend\Response();
        $this->view = new \Jaxon\Zend\View();

        $appPath = rtrim(getcwd(), '/');
        $config = require($appPath . '/config/jaxon.config.php');
        $appConfig = array_key_exists('app', $config) ? $config['app'] : array();
        $libConfig = array_key_exists('lib', $config) ? $config['lib'] : array();

        // Jaxon application settings
        $controllerDir = (array_key_exists('dir', $appConfig) ? $appConfig['dir'] : $appPath . '/jaxon');
        $namespace = (array_key_exists('namespace', $appConfig) ? $appConfig['namespace'] : '\\Jaxon\\App');

        $excluded = (array_key_exists('excluded', $appConfig) ? $appConfig['excluded'] : array());
        // The public methods of the Controller base class must not be exported to javascript
        $controllerClass = new \ReflectionClass('\\Jaxon\\Zend\\Controller');
        foreach ($controllerClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $xMethod)
        {
            $excluded[] = $xMethod->getShortName();
        }
        // Use the Composer autoloader
        $this->jaxon->useComposerAutoloader();
        // Jaxon library default options
        $this->jaxon->setOptions(array(
            'js.app.extern' => false,
            'js.app.minify' => false,
        ));
        // Jaxon library settings
        \Jaxon\Config\Config::setOptions($libConfig);
        // Set the request URI
        if(!$this->jaxon->getOption('core.request.uri'))
        {
            $this->jaxon->setOption('core.request.uri', 'jaxon');
        }
        // Register the default Jaxon class directory
        $this->jaxon->addClassDir($controllerDir, $namespace, $excluded);
    }
}
