<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Database\DB;

class FrontendController extends Controller{

    public function index() : void{
        $data = [
            'session' => $this->session,
            'isGuest' => $this->auth->isGuest(),
        ];
        $html = $this->renderer->render('index.html', $data);
        $this->response->setContent($html);
    }

    public function login() : void{
        $html = $this->renderer->render('login.html',['session' => $this->session, 'isGuest' => $this->auth->isGuest()]);
        $this->response->setContent($html);
    }

    public function logout() : void{
        $html = $this->renderer->render('logout.html',['session' => $this->session, 'isGuest' => $this->auth->isGuest()]);
        $this->response->setContent($html);
    }

    public function register() : void{
        $html = $this->renderer->render('register.html',['session' => $this->session, 'isGuest' => $this->auth->isGuest()]);
        $this->response->setContent($html);
    }

    public function messages() : void{
        $html = $this->renderer->render('messages.html',['session' => $this->session, 'isGuest' => $this->auth->isGuest()]);
        $this->response->setContent($html);
    }
}