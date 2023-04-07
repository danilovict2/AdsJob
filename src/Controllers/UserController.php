<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\User;

class UserController extends Controller{

    public function store() : void{
        $validator = new \AdsJob\Validators\Validator([
            'firstName' => ['required'],
            'lastName' => ['required'],
            'email' => ['email', 'required', ['unique' => 'User']],
            'password' => ['required', ['min' => 8]],
            'confirmPassword' => ['required', ['match' => 'password']],
        ]);
        $user = new User;
        $user->create($this->request->getBodyParameters());
        if($validator->validateForm($this->request->getBodyParameters())){
            $user->save();
            $this->response->redirect('/');
        }else{
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/register');
        }
    }

}