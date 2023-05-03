<?php declare(strict_types = 1);

namespace AdsJob\Controllers\Auth;
use AdsJob\Controllers\Controller;
use AdsJob\Models\User;

class LoginController extends Controller{

    public function login() : void{
        if(!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            die;
        }
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
            $validator->addError('email', 'Korisnik sa ovim E-mail ne postoji');
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/login');
            return;
        }
        
        $passwordValid = password_verify($formData['password'], $user->password);
        if (!$passwordValid) {
            $validator->addError('password', 'Lozinka koju ste uneli je netačna');
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