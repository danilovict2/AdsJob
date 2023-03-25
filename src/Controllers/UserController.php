<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\User;

class UserController extends Controller{

    public function store(){
        $user = new User();
        $user->loadData($this->request->getBodyParameters());
        $user->validateRequest();
        $html = $this->renderer->render('index.html');
        $this->response->setContent($html);
    }
}