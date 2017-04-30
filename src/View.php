<?php

namespace Jaxon\Zend;

use Zend\View\Renderer\RendererInterface;
use Zend\View\Model\ViewModel;

use Jaxon\Sentry\View\Store;
use Jaxon\Sentry\Interfaces\View as ViewInterface;

class View implements ViewInterface
{
    protected $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Add a namespace to this view renderer
     *
     * @param string        $sNamespace         The namespace name
     * @param string        $sDirectory         The namespace directory
     * @param string        $sExtension         The extension to append to template names
     *
     * @return void
     */
    public function addNamespace($sNamespace, $sDirectory, $sExtension = '')
    {}

    /**
     * Render a view
     * 
     * @param Store         $store        A store populated with the view data
     * 
     * @return string        The string representation of the view
     */
    public function render(Store $store)
    {
        // Render the view
        $view = new ViewModel($store->getViewData());
        $view->setTemplate($store->getViewName());
        $view->setTerminal(true);
        return trim($this->renderer->render($view), " \t\n");
    }
}
