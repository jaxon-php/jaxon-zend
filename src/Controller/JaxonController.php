<?php

namespace Jaxon\Zend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class JaxonController extends AbstractActionController
{
    public function indexAction()
    {
        // Get the Jaxon plugin
        $jaxon = $this->getServiceLocator()->get('JaxonPlugin');
        // Process Jaxon request
        if($jaxon->canProcessRequest())
        {
            $jaxon->processRequest();
        }
    }
}
