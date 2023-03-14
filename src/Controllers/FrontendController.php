<?php declare(strict_types = 1);

namespace AdsJob\Controllers;


class FrontendController extends Controller{

    public function index() : void{
        $data = [
            'name' => $this->request->getParameter('name', 'stranger'),
            'id' => $this->database->rawQuery("SELECT id FROM test WHERE id = :id", ['id' => 1])->fetch(),
        ];
        $html = $this->renderer->render('index.html', $data);
        $this->response->setContent($html);
    }

    public function postFindJob() : void{
        $html = $this->renderer->render('post-find-job.html');
        $this->response->setContent($html);
    }

    public function login() : void{
        $html = $this->renderer->render('login.html');
        $this->response->setContent($html);
    }

    public function register() : void{
        $html = $this->renderer->render('register.html');
        $this->response->setContent($html);
    }
}