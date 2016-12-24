<?php

namespace Jaxon\Zend\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Http\Response as HttpResponse;

class JaxonPlugin extends AbstractPlugin
{
    use \Jaxon\Module\Traits\Module;

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
     * Set the module specific options for the Jaxon library.
     *
     * @return void
     */
    protected function setup()
    {
        // The application debug option
        $debug = (getenv('APP_ENV') != 'production');
        // The application root dir
        $appPath = rtrim(getcwd(), '/');
        // The application URL
        $baseUrl = $_SERVER['SERVER_NAME'];
        // The application web dir
        $baseDir = $_SERVER['DOCUMENT_ROOT'];

        // Read and set the config options from the config file
        $jaxon = jaxon();
        $this->appConfig = $jaxon->readConfigFile($appPath . '/config/jaxon.config.php', 'lib', 'app');

        // Jaxon library settings
        // Default values
        if(!$jaxon->hasOption('js.app.extern'))
        {
            $jaxon->setOption('js.app.extern', !$debug);
        }
        if(!$jaxon->hasOption('js.app.minify'))
        {
            $jaxon->setOption('js.app.minify', !$debug);
        }
        if(!$jaxon->hasOption('js.app.uri'))
        {
            $jaxon->setOption('js.app.uri', '//' . $baseUrl . '/jaxon/js');
        }
        if(!$jaxon->hasOption('js.app.dir'))
        {
            $jaxon->setOption('js.app.dir', $baseDir . '/jaxon/js');
        }

        // Jaxon application settings
        // Default values
        if(!$this->appConfig->hasOption('controllers.directory'))
        {
            $this->appConfig->setOption('controllers.directory', $appPath . '/jaxon/Controllers');
        }
        if(!$this->appConfig->hasOption('controllers.namespace'))
        {
            $this->appConfig->setOption('controllers.namespace', '\\Jaxon\\App');
        }
        if(!$this->appConfig->hasOption('controllers.protected') || !is_array($this->appConfig->getOption('protected')))
        {
            $this->appConfig->setOption('controllers.protected', array());
        }
        // Jaxon controller class
        $this->setControllerClass('\\Jaxon\\Zend\\Controller');
    }

    /**
     * Set the module specific options for the Jaxon library.
     *
     * This method needs to set at least the Jaxon request URI.
     *
     * @return void
     */
    protected function check()
    {
        // Todo: check the mandatory options
    }

    /**
     * Return the view renderer.
     *
     * @return void
     */
    protected function view()
    {
        if($this->viewRenderer == null)
        {
            $this->viewRenderer = new \Jaxon\Zend\View($this->renderer);
        }
        return $this->viewRenderer;
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
