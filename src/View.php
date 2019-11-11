<?php

namespace Jaxon\Zend;

use Zend\View\Renderer\RendererInterface;
use Zend\View\Model\ViewModel;

use Jaxon\Utils\View\Store;
use Jaxon\Contracts\View as ViewContract;

class View implements ViewContract
{
    protected $xRenderer;

    public function __construct(RendererInterface $xRenderer)
    {
        $this->xRenderer = $xRenderer;
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
        return trim($this->xRenderer->render($view), " \t\n");
    }
}
