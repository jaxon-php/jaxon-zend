<?php

namespace Jaxon\Zend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class JaxonController extends AbstractActionController
{
    public function indexAction()
    {
        // Get the Jaxon controller plugin
        $jaxon = $this->jaxon();
        // Process Jaxon request
        if($jaxon->canProcessRequest())
        {
            $jaxon->processRequest();
        }
    }
}
