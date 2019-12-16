<?php

namespace Jaxon\Zend\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Http\Response as HttpResponse;
use Zend\View\Renderer\RendererInterface;

class JaxonPlugin extends AbstractPlugin
{
    use \Jaxon\Features\App;

    /**
     * @var ContainerInterface|ServiceLocatorInterface      $container
     */
    protected $xContainer;

    /**
     * Setup Jaxon with the view renderer and the DI container.
     *
     * @param ContainerInterface|ServiceLocatorInterface    $container
     *
     * @return void
     */
    public function setContainer($xContainer)
    {
        $this->xContainer = $xContainer;

        // Initialize the Jaxon plugin
        $this->jaxonSetup();
    }

    /**
     * Set the module specific options for the Jaxon library.
     *
     * @return void
     */
    protected function jaxonSetup()
    {
        // The application debug option
        $bIsDebug = (getenv('APP_ENV') != 'production');
        // The application root dir
        $sAppPath = rtrim(getcwd(), '/');
        // The application URL
        $sJsUrl = '//' . $_SERVER['SERVER_NAME'] . '/jaxon/js';
        // The application web dir
        $sJsDir = $_SERVER['DOCUMENT_ROOT'] . '/jaxon/js';

        $jaxon = jaxon();
        $di = $jaxon->di();

        // Read the config options.
        $aOptions = $jaxon->config()->read($sAppPath . '/config/jaxon.config.php');
        $aLibOptions = key_exists('lib', $aOptions) ? $aOptions['lib'] : [];
        $aAppOptions = key_exists('app', $aOptions) ? $aOptions['app'] : [];

        $viewManager = $di->getViewManager();
        // Set the default view namespace
        $viewManager->addNamespace('default', '', '', 'zend');
        // Add the view renderer
        $viewManager->addRenderer('zend', function() {
            return new \Jaxon\Zend\View($this->xContainer->get('ViewRenderer'));
        });

        // Set the session manager
        $di->setSessionManager(function() {
            return new \Jaxon\Zend\Session();
        });

        // Set the framework service container wrapper
        $di->setAppContainer(new \Jaxon\Zend\Container($this->xContainer));

        $this->bootstrap()
            ->lib($aLibOptions)
            ->app($aAppOptions)
            // ->uri($sUri)
            ->js(!$bIsDebug, $sJsUrl, $sJsDir, !$bIsDebug)
            ->run();

        // Prevent the Jaxon library from sending the response or exiting
        $jaxon->setOption('core.response.send', false);
        $jaxon->setOption('core.process.exit', false);
    }

    /**
     * Get the HTTP response
     *
     * @param string    $code       The HTTP response code
     *
     * @return mixed
     */
    public function httpResponse($code = '200')
    {
        $jaxon = jaxon();
        // Get the reponse to the request
        $jaxonResponse = $jaxon->di()->getResponseManager()->getResponse();
        if(!$jaxonResponse)
        {
            $jaxonResponse = $jaxon->getResponse();
        }

        // Create and return a ZF2 HTTP response
        $httpResponse = new HttpResponse();
        $headers = $httpResponse->getHeaders();
        $headers->addHeaderLine('Content-Type', $jaxonResponse->getContentType() .
            '; charset=' . $jaxonResponse->getCharacterEncoding());
        $httpResponse->setStatusCode(intval($code));
        $httpResponse->setContent($jaxonResponse->getOutput());
        return $httpResponse;
    }

    /**
     * Process an incoming Jaxon request, and return the response.
     *
     * @return mixed
     */
    public function processRequest()
    {
        // Process the jaxon request
        jaxon()->processRequest();

        // Return the reponse to the request
        return $this->httpResponse();
    }
}
