<?php

namespace Jaxon\Zend\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Http\Response as HttpResponse;

class JaxonPlugin extends AbstractPlugin
{
    use \Jaxon\Framework\PluginTrait;

    /**
     * The template engine
     * 
     * @var \Zend\View\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * Create a new Jaxon instance.
     *
     * @return void
     */
    public function __construct($renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Setup the Jaxon module.
     *
     * @return void
     */
    public function setup()
    {
        $this->view = new \Jaxon\Zend\View($this->renderer);
        // The application debug option
        $debug = (getenv('APP_ENV') != 'production');
        // The application root dir
        $appPath = rtrim(getcwd(), '/');
        // The application URL
        $baseUrl = $_SERVER['SERVER_NAME'];
        // The application web dir
        $baseDir = $_SERVER['DOCUMENT_ROOT'];

        // Jaxon library default options
        $this->jaxon->setOptions(array(
            'js.app.extern' => !$debug,
            'js.app.minify' => !$debug,
            'js.app.uri' => '//' . $baseUrl . '/jaxon/js',
            'js.app.dir' => $baseDir . '/jaxon/js',
        ));
        // Jaxon library settings
        $config = $this->jaxon->readConfigFile($appPath . '/config/jaxon.config.php', 'lib');

        // Jaxon application settings
        $appConfig = array();
        if(array_key_exists('app', $config) && is_array($config['app']))
        {
            $appConfig = $config['app'];
        }
        $controllerDir = (array_key_exists('dir', $appConfig) ? $appConfig['dir'] : $appPath . '/jaxon');
        $namespace = (array_key_exists('namespace', $appConfig) ? $appConfig['namespace'] : '\\Jaxon\\App');
        $excluded = (array_key_exists('excluded', $appConfig) ? $appConfig['excluded'] : array());
        // The public methods of the Controller base class must not be exported to javascript
        $controllerClass = new \ReflectionClass('\\Jaxon\\Zend\\Controller');
        foreach ($controllerClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $xMethod)
        {
            $excluded[] = $xMethod->getShortName();
        }

        // Set the request URI
        if(!$this->jaxon->getOption('core.request.uri'))
        {
            $this->jaxon->setOption('core.request.uri', 'jaxon');
        }
        // Register the default Jaxon class directory
        $this->jaxon->addClassDir($controllerDir, $namespace, $excluded);
    }

    /**
     * Wrap the Jaxon response into an HTTP response.
     *
     * @param  $code        The HTTP Response code
     *
     * @return \Zend\Http\Response
     */
    public function httpResponse($code = '200')
    {
        // Send HTTP Headers
        // $this->response->sendHeaders();
        // Create and return a ZF2 HTTP response
        $response = new HttpResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', $this->response->getContentType() .
            '; charset=' . $this->response->getCharacterEncoding());
        $response->setStatusCode(intval($code));
        $response->setContent($this->response->getOutput());
        return $response;
    }
}
