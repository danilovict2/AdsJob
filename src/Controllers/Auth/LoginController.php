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
            $validator->addError('email', 'Korisnik sa ovim E-mail-om ne postoji');
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/login');
            return;
        }

        if(!$user->email_verified_at){
            $validator->addError('email', 'Molimo vas da potvrdite E-mail');
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/login');
            return;
        }
        
        $passwordValid = password_verify($formData['password'], $user->password);
        if (!$passwordValid) {
            $validator->addError('password', 'Lozinka koju ste uneli nije tačna');
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/login');
            return;
        }

        $unverified_user_id = $this->request->getCookie('unverified_user_id');
        if(isset($unverified_user_id)){
            setcookie('unverified_user_id', "$user->id", time() - 3600, secure:true, path:'/');
            unset($_COOKIE['unverified_user_id']);
        }
        $this->auth->login($user);
        $this->response->redirect('/');
    }

    public function logout(){
        $this->auth->logout();
        $this->response->redirect('/');
    }

}