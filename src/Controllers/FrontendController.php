<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Models\User;

class FrontendController extends Controller{

    public function middleware(){
        $this->registerMiddleware(new \AdsJob\Middleware\RedirectIfAuthenticatedMiddleware($this->auth, ['login', 'register']));
    }

    public function index() : void{
        $html = $this->renderer->render('index.html', $this->requiredData);
        $this->response->setContent($html);
    }

    public function login() : void{
        $html = $this->renderer->render('login.html',$this->requiredData);
        $this->response->setContent($html);
    }

    public function register() : void{
        $html = $this->renderer->render('register.html',$this->requiredData);
        $this->response->setContent($html);
    }

    public function messages() : void{
        $html = $this->renderer->render('messages.html',$this->requiredData);
        $this->response->setContent($html);
    }
}