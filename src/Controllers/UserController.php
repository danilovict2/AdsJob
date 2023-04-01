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
        $validator = new \AdsJob\Validators\Validator($rules);
        $user = new User($this->request->getBodyParameters());
        if($validator->validateForm($this->request->getBodyParameters())){
            $user->save();
            $this->response->redirect('/');
        }else{
            foreach($validator->errors as $key => $errorMessages){
                $this->session->setFlash($key, $errorMessages[0]);
            }
        }
        $this->response->redirect('/register');
    }

}