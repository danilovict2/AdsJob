<?php declare(strict_types = 1);

namespace AdsJob\Controllers;


class ErrorController extends Controller{

    public function error404() : void{
        $html = $this->renderer->render('404.html');
        $this->response->setContent($html);
        $this->response->setStatusCode(404);
    }

    public function error405() : void{
        $html = $this->renderer->render('405.html');
        $this->response->setContent($html);
        $this->response->setStatusCode(405);
    }
}