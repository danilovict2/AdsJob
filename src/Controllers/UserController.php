<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\User;

class UserController extends Controller{

    public function store(){
        $rules = [
            'firstName' => ['required'],
            'lastName' => ['required'],
            'email' => ['email', 'required', ['unique' => 'User']],
            'password' => ['required', ['min' => 8]],
            'confirmPassword' => ['required', ['match' => 'password']],
        ];
        $validator = new \AdsJob\Validators\Validator($rules,$this->database);
        $html = $this->renderer->render('index.html');
        if(!$validator->validateForm($this->request->getBodyParameters())){
            $html = $this->renderer->render('register.html', ['validator' => $validator]);
        }else{
            $user = new User($this->database, $this->request->getBodyParameters());
            $user->save();
        }
        $this->response->setContent($html);
    }
}