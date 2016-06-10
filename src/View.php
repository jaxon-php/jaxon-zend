<?php

namespace Jaxon\Zend;

class View
{
    protected static $data;

    public function __construct()
    {
        if(!is_array(self::$data))
        {
            self::$data = array();
        }
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
        self::$data[$name] = $value;
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
        $view = new \ViewModel(array_merge(self::$data, $data));
        $view->setTemplate($template);
        return $view;
    }
}
