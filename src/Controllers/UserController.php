<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\User;
use AdsJob\Middleware\AuthMiddleware;

class UserController extends Controller{

    public function middleware(){
        $this->registerMiddleware(new AuthMiddleware($this->auth,['index']));
    }
    
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

    public function update(){
        $user = User::findOne(['id' => $this->auth->user()->id]);
        $user->update($this->request->getBodyParameters());
        $this->response->redirect('/');
    }

    public function profile() : void{
        $html = $this->renderer->render('profile.html',$this->requiredData);
        $this->response->setContent($html);
    }

    public function myReviews() : void{
        $html = $this->renderer->render('reviews.html',$this->requiredData);
        $this->response->setContent($html);
    }

    public function editProfile() : void{
        $data = array_merge($this->requiredData, ['user' => $this->auth->user()]);
        $html = $this->renderer->render('editProfile.html',$data);
        $this->response->setContent($html);
    }

    public function myJobs() : void{
        $html = $this->renderer->render('myJobs.html', $this->requiredData);
        $this->response->setContent($html);
    }

}