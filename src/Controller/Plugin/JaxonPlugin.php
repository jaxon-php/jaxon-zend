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

        $this->jaxon()
            ->lib($aLibOptions)
            ->app($aAppOptions)
            // ->uri($sUri)
            ->js(!$bIsDebug, $sJsUrl, $sJsDir, !$bIsDebug)
            ->bootstrap(false);
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
        // $this->ajaxResponse()->sendHeaders();
        // Create and return a ZF2 HTTP response
        $response = new HttpResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', $this->ajaxResponse()->getContentType() .
            '; charset=' . $this->ajaxResponse()->getCharacterEncoding());
        $response->setStatusCode(intval($code));
        $response->setContent($this->ajaxResponse()->getOutput());
        return $response;
    }
}
