<?php

namespace Jaxon\Zend;

use Zend\View\Renderer\RendererInterface;
use Zend\View\Model\ViewModel;

class View
{
    protected $data;
    protected $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->data = array();
        $this->renderer = $renderer;
    }

    /**
     * Make a piece of data available for all views
     *
     * @param string        $name            The data name
     * @param string        $value            The data value
     * 
     * @return void
     */
    public function share($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Render a template
     *
     * @param string        $template        The template path
     * @param string        $data            The template data
     * 
     * @return mixed        The rendered template
     */
    public function render($template, array $data = array())
    {
        $view = new ViewModel(array_merge($this->data, $data));
        $view->setTemplate($template);
        $view->setTerminal(true);
        return trim($this->renderer->render($view), "\n");
    }
}
