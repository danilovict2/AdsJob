<?php declare(strict_types = 1);

namespace AdsJob\Controllers\Auth;
use AdsJob\Controllers\Controller;
use AdsJob\Models\User;

class LoginController extends Controller{

    public function login() : void{
        $validator = new \AdsJob\Validators\Validator([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        
        $formData = $this->request->getBodyParameters();
        if (!$validator->validateForm($formData)) {
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/login');
            return;
        }
        
        $user = User::findOne(['email' => $formData['email']]);
        if (!$user) {
            $validator->addError('email', 'User with this email does not exist');
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/login');
            return;
        }
        
        $passwordValid = password_verify($formData['password'], $user->password);
        if (!$passwordValid) {
            $validator->addError('password', 'Password is incorrect');
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/login');
            return;
        }
        $this->auth->login($user);
        $this->response->redirect('/');
    }

    public function logout(){
        $this->auth->logout();
        $this->response->redirect('/');
    }

}