<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\User;

class UserController extends Controller{

    public function store(){
        $user = new User();
        $user->loadData($this->request->getBodyParameters());
        $html = $this->renderer->render('index.html');
        if(!$user->validateRequest()){
            $data = [
          
            ];
            $html = $this->renderer->render('register.html',$data);
        }
        $this->response->setContent($html);
    }
}