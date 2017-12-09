<?php

namespace Jaxon\Zend\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Http\Response as HttpResponse;
use Zend\View\Renderer\RendererInterface;

class JaxonPlugin extends AbstractPlugin
{
    use \Jaxon\Sentry\Traits\Armada;

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
        $this->_jaxonSetup();
    }

    /**
     * Set the module specific options for the Jaxon library.
     *
     * @return void
     */
    protected function jaxonSetup()
    {
        // The application debug option
        $isDebug = (getenv('APP_ENV') != 'production');
        // The application root dir
        $appPath = rtrim(getcwd(), '/');
        // The application URL
        $baseUrl = '//' . $_SERVER['SERVER_NAME'];
        // The application web dir
        $baseDir = $_SERVER['DOCUMENT_ROOT'];

        $jaxon = jaxon();
        $sentry = $jaxon->sentry();

        // Read and set the config options from the config file
        $this->appConfig = $jaxon->readConfigFile($appPath . '/config/jaxon.config.php', 'lib', 'app');

        // Jaxon library default settings
        $sentry->setLibraryOptions(!$isDebug, !$isDebug, $baseUrl . '/jaxon/js', $baseDir . '/jaxon/js');

        // Set the default view namespace
        $sentry->addViewNamespace('default', '', '', 'zend');
        $this->appConfig->setOption('options.views.default', 'default');

        // Add the view renderer
        $renderer = $this->xViewRenderer;
        $sentry->addViewRenderer('zend', function () use ($renderer) {
            return new \Jaxon\Zend\View($renderer);
        });

        // Set the session manager
        $sentry->setSessionManager(function () {
            return new \Jaxon\Zend\Session();
        });
    }

    /**
     * Set the module specific options for the Jaxon library.
     *
     * This method needs to set at least the Jaxon request URI.
     *
     * @return void
     */
    protected function jaxonCheck()
    {
        // Todo: check the mandatory options
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
