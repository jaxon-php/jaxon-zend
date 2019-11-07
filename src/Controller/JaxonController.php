<?php

namespace Jaxon\Zend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Jaxon\Zend\Controller\Plugin\JaxonPlugin;

class JaxonController extends AbstractActionController
{
    /**
     * @var \Jaxon\Zend\Controller\Plugin\JaxonPlugin
     */
    protected $jaxon;

    /**
     * The Jaxon Controller constructor
     *
     * The parameter is automatically populated by Zend.
     *
     * @param JaxonPlugin $jaxon
     */
    public function __construct(JaxonPlugin $jaxon)
    {
        $this->jaxon = $jaxon;
    }

    /**
     * Process a Jaxon request.
     *
     * The HTTP response is automatically sent back to the browser
     *
     * @return void
     */
    public function indexAction()
    {
        $this->jaxon->callback()->before(function ($target, &$bEndRequest) {
            /*
            if($target->isFunction())
            {
                $function = $target->getFunctionName();
            }
            elseif($target->isClass())
            {
                $class = $target->getClassName();
                $method = $target->getMethodName();
                // $instance = $this->jaxon->instance($class);
            }
            */
        });
        $this->jaxon->callback()->after(function ($target, $bEndRequest) {
            /*
            if($target->isFunction())
            {
                $function = $target->getFunctionName();
            }
            elseif($target->isClass())
            {
                $class = $target->getClassName();
                $method = $target->getMethodName();
            }
            */
        });

        // Process the Jaxon request
        if($this->jaxon->canProcessRequest())
        {
            $this->jaxon->processRequest();
            return $this->jaxon->httpResponse();
        }
    }
}
