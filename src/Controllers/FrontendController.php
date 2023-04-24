<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

class FrontendController extends Controller{

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