<?php declare(strict_types = 1);

namespace AdsJob\Controllers;


class FrontendController extends Controller{

    public function index(){
        $data = [
            'name' => $this->request->getParameter('name', 'stranger'),
        ];
        $html = $this->renderer->render('index.html', $data);
        $this->response->setContent($html);
    }

    public function showPostFindJob(){
        $html = $this->renderer->render('post-find-job.html');
        $this->response->setContent($html);
    }

    public function showLogin(){
        $html = $this->renderer->render('login.html');
        $this->response->setContent($html);
    }

    public function showRegister(){
        $html = $this->renderer->render('register.html');
        $this->response->setContent($html);
    }
}