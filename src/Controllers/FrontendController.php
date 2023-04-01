<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Database\DB;

class FrontendController extends Controller{

    public function index() : void{
        $data = [
            'name' => $this->request->getParameter('name', 'stranger'),
            'id' => DB::rawQuery("SELECT COUNT(id) AS idCount FROM test")->fetch(),
            'session' => $this->session
        ];
        $html = $this->renderer->render('index.html', $data);
        $this->response->setContent($html);
    }

    public function login() : void{
        $html = $this->renderer->render('login.html',['session' => $this->session]);
        $this->response->setContent($html);
    }

    public function register() : void{
        $html = $this->renderer->render('register.html',['session' => $this->session]);
        $this->response->setContent($html);
    }

    public function messages() : void{
        $html = $this->renderer->render('messages.html',['session' => $this->session]);
        $this->response->setContent($html);
    }
}