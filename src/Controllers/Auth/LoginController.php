<?php declare(strict_types = 1);

namespace AdsJob\Controllers\Auth;
use AdsJob\Controllers\Controller;
use AdsJob\Models\User;

class LoginController extends Controller{

    public function login(){
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
        $validator = new \AdsJob\Validators\Validator($rules);
        if(!$validator->validateForm($this->request->getBodyParameters())){
            foreach($validator->errors as $key => $errorMessages){
                $this->session->setFlash($key, $errorMessages[0]);
            }  
            $this->response->redirect('/login');
        }
        $user = User::findOne(['email' => $this->request->getBodyParameter('email')]);
        if(!$user){
            $validator->addError('email', "User with this email does not exist");
            foreach($validator->errors as $key => $errorMessages){
                $this->session->setFlash($key, $errorMessages[0]);
            }
            $this->response->redirect('/login');
        }
        if(!password_verify($user->password, $this->request->getBodyParameter('password'))){
            $validator->addError('password', "Password and email don't match");
            foreach($validator->errors as $key => $errorMessages){
                $this->session->setFlash($key, $errorMessages[0]);
            }
            $this->response->redirect('/login');
        }
        $this->response->redirect('/');
    }

}