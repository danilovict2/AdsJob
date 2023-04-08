<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Template\FrontendRenderer;
use Http\Response;
use Http\Request;
use AdsJob\Sessions\Session;
use AdsJob\Auth\Auth;
use AdsJob\Middleware\Middleware;

class Controller{

    private array $middleware = [];
    protected array $requiredData = [];

    public function __construct(
        protected Request $request, 
        protected Response $response,
        protected FrontendRenderer $renderer,
        protected Session $session,
        protected Auth $auth,
    ){
        $this->requiredData = ['isGuest' => $this->auth->isGuest(), 'session' => $this->session];
    }

    protected function setValidationErrors(array $errors) : void{
        foreach ($errors as $key => $messages) {
            $this->session->setFlash($key, $messages[0]);
        }
    }

    public function middleware(){
        
    }

    protected function registerMiddleware(Middleware $middleware) : void{
        $this->middleware[] = $middleware;
    }

    public function getMiddleware() : array{
        return $this->middleware;
    }
}