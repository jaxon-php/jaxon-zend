<?php

namespace Jaxon\Zend\Controller\Plugin;

use Jaxon\Config\Php as Config;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Http\Response as HttpResponse;
use Zend\View\Renderer\RendererInterface;

class JaxonPlugin extends AbstractPlugin
{
    use \Jaxon\Module\Traits\Module;

    /**
     * Create the Jaxon view renderer, using the Zend renderer.
     *
     * @return void
     */
    public function jaxonSetRenderer(RendererInterface $renderer)
    {
        if($this->jaxonViewRenderer == null)
        {
            $this->jaxonViewRenderer = new \Jaxon\Zend\View($renderer);
        }
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

        // Read and set the config options from the config file
        $this->appConfig = Config::read($appPath . '/config/jaxon.config.php', 'lib', 'app');

        // Jaxon library default settings
        $this->setLibraryOptions(!$isDebug, !$isDebug, $baseUrl . '/jaxon/js', $baseDir . '/jaxon/js');

        // Jaxon application default settings
        $this->setApplicationOptions($appPath . '/jaxon/Controller', '\\Jaxon\\App');

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
    protected function jaxonCheck()
    {
        // Todo: check the mandatory options
    }

    /**
     * Return the view renderer.
     *
     * @return void
     */
    protected function jaxonView()
    {
        return $this->jaxonViewRenderer;
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
