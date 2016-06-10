<?php

namespace Jaxon\Zend;

use Zend\Http\Response as HttpResponse;

class Response extends \Jaxon\Response\Response
{
    /**
     * Create a new Response instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Wrap the Jaxon response in a ZF2 HTTP response.
     *
     * @param  string  $code
     *
     * @return string  the HTTP response
     */
    public function http($code = '200')
    {
        // Create and return a ZF2 HTTP response
        $response = new HttpResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', $this->getContentType() . '; charset=' . $this->getCharacterEncoding());
        $response->setStatusCode(intval($code));
        $response->setContent($this->getOutput());
        return $response;
    }
}
