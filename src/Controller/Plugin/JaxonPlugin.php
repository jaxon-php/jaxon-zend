<?php

namespace Jaxon\Zend\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Http\Response as HttpResponse;
use Zend\View\Renderer\RendererInterface;

use function rtrim;
use function intval;
use function getenv;
use function getcwd;
use function jaxon;

class JaxonPlugin extends AbstractPlugin
{
    use \Jaxon\App\AppTrait;

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

        $this->jaxon = jaxon();
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
        // Set the default view namespace
        $this->addViewNamespace('default', '', '', 'zend');
        // Add the view renderer
        $this->addViewRenderer('zend', function() {
            return new \Jaxon\Zend\View($this->xContainer->get('ViewRenderer'));
        });
        // Set the session manager
        $this->setSessionManager(function() {
            return new \Jaxon\Zend\Session();
        });
        // Set the framework service container wrapper
        $this->setAppContainer(new \Jaxon\Zend\Container($this->xContainer));

        // The application debug option
        $bIsDebug = (getenv('APP_ENV') != 'production');
        // The application root dir
        $sAppPath = rtrim(getcwd(), '/');
        // The application URL
        $sJsUrl = '//' . $_SERVER['SERVER_NAME'] . '/jaxon/js';
        // The application web dir
        $sJsDir = $_SERVER['DOCUMENT_ROOT'] . '/jaxon/js';

        // Read the config options.
        $aOptions = $this->jaxon->readConfig($sAppPath . '/config/jaxon.config.php');
        $aLibOptions = $aOptions['lib'] ?? [];
        $aAppOptions = $aOptions['app'] ?? [];

        $this->bootstrap()
            ->lib($aLibOptions)
            ->app($aAppOptions)
            ->asset(!$bIsDebug, !$bIsDebug, $sJsUrl, $sJsDir)
            ->setup();
    }

    /**
     * @inheritDoc
     */
    public function httpResponse($sCode = '200')
    {
        // Get the reponse to the request
        $jaxonResponse = $this->jaxon->getResponse();

        // Create and return a ZF2 HTTP response
        $httpResponse = new HttpResponse();
        $headers = $httpResponse->getHeaders();
        $headers->addHeaderLine('Content-Type', $this->getContentType());
        $httpResponse->setStatusCode(intval($sCode));
        $httpResponse->setContent($jaxonResponse->getOutput());
        return $httpResponse;
    }
}
