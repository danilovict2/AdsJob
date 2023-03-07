<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

class JobController extends Controller{

    public function create() : void{
        $html = $this->renderer->render('postJob.html');
        $this->response->setContent($html);
    }
}