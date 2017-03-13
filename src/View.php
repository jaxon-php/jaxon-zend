<?php

namespace Jaxon\Zend;

use Zend\View\Renderer\RendererInterface;
use Zend\View\Model\ViewModel;

use Jaxon\Module\View\Store;
use Jaxon\Module\View\Facade;

class View extends Facade
{
    protected $renderer;

    public function __construct(RendererInterface $renderer)
    {
        parent::__construct();
        $this->renderer = $renderer;
    }

    /**
     * Render a view
     * 
     * @param Store         $store        A store populated with the view data
     * 
     * @return string        The string representation of the view
     */
    public function make(Store $store)
    {
        // Render the view
        $view = new ViewModel($store->getViewData());
        $view->setTemplate($store->getViewPath());
        $view->setTerminal(true);
        return trim($this->renderer->render($view), " \t\n");
    }
}
