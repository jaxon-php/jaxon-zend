<?php

namespace Jaxon\Zend\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Http\Response as HttpResponse;
use Zend\View\Renderer\RendererInterface;

class JaxonPlugin extends AbstractPlugin
{
    use \Jaxon\Features\App;

    /**
     * The Zend View Renderer
     *
     * @var RendererInterface
     */
    private $xViewRenderer = null;

    /**
     * Create the Jaxon view renderer, using the Zend renderer.
     *
     * @param  $xRenderer        The Zend Framework view renderer
     *
     * @return void
     */
    public function setZendViewRenderer(RendererInterface $xRenderer)
    {
        $this->xViewRenderer = $xRenderer;

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

        $viewManager = $di->getViewmanager();
        // Set the default view namespace
        $viewManager->addNamespace('default', '', '', 'zend');
        // Add the view renderer
        $viewManager->addRenderer('zend', function () {
            return new \Jaxon\Zend\View($this->xViewRenderer);
        });

        // Set the session manager
        $di->setSessionManager(function () {
            return new \Jaxon\Zend\Session();
        });

        $this->bootstrap()
            ->lib($aLibOptions)
            ->app($aAppOptions)
            // ->uri($sUri)
            ->js(!$bIsDebug, $sJsUrl, $sJsDir, !$bIsDebug)
            ->run(false);

        // Prevent the Jaxon library from sending the response or exiting
        $jaxon->setOption('core.response.send', false);
        $jaxon->setOption('core.process.exit', false);
    }

    /**
     * Process an incoming Jaxon request, and return the response.
     *
     * @return mixed
     */
    public function processRequest()
    {
        $jaxon = jaxon();
        // Process the jaxon request
        $jaxon->processRequest();
        // Get the reponse to the request
        $jaxonResponse = $jaxon->di()->getResponseManager()->getResponse();

        // Create and return a ZF2 HTTP response
        $code = '200';
        $response = new HttpResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', $jaxonResponse->getContentType() .
            '; charset=' . $jaxonResponse->getCharacterEncoding());
        $response->setStatusCode(intval($code));
        $response->setContent($jaxonResponse->getOutput());
        return $response;
    }
}
