<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Template\FrontendRenderer;
use Http\Response;
use Http\Request;
use AdsJob\Sessions\Session;
use AdsJob\Auth\Auth;

class Controller{

    public function __construct(
        protected Request $request, 
        protected Response $response,
        protected FrontendRenderer $renderer,
        protected Session $session,
        protected Auth $auth,
    ){
        
    }

    protected function setValidationErrors(array $errors) : void{
        foreach ($errors as $key => $messages) {
            $this->session->setFlash($key, $messages[0]);
        }
    }
}