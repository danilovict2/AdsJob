<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\User;

class UserController extends Controller{

    public function store(){
        $rules = [
            'firstName' => ['required'],
            'lastName' => ['required'],
            'email' => ['email', 'required'],
            'password' => ['required', ['min' => 8]],
            'confirmPassword' => ['required', ['match' => 'password']],
        ];
        $validator = new \AdsJob\Validators\Validator($rules);
        $validator->validateForm($this->request->getBodyParameters());
        $html = $this->renderer->render('index.html');
        $this->response->setContent($html);
    }
}