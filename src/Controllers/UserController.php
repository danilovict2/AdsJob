<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\User;
use AdsJob\Middleware\AuthMiddleware;
use AdsJob\Middleware\RedirectIfAuthenticatedMiddleware;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class UserController extends Controller{

    public function middleware(){
        $this->registerMiddleware(new RedirectIfAuthenticatedMiddleware($this->auth,['profile', 'editProfile', 'myJobs']));
    }
    
    public function store() : void{
        $validator = new \AdsJob\Validators\Validator([
            'firstName' => ['required', ['max' => 12]],
            'lastName' => ['required' , ['max' => 12]],
            'email' => ['email', 'required', ['unique' => 'User']],
            'password' => ['required', ['min' => 8]],
            'confirmPassword' => ['required', ['match' => 'password']],
        ]);
        $user = new User;
        $user->create($this->request->getBodyParameters());
        if($validator->validateForm($this->request->getBodyParameters())){
            $user->save();
            $this->auth->login(User::findOne(['email' => $user->email]));
            $this->response->redirect('/');
        }else{
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/register');
        }
    }

    public function update() : void{
        $user = $this->auth->user();
        $validator = new \AdsJob\Validators\Validator([
            'firstName' => ['required', ['max' => 12]],
            'lastName' => ['required', ['max' => 12]],
            'email' => ['email', $user->email !== $this->request->getBodyParameter('email') ? ['unique' => 'User'] : ''],
            'oldPassword' => [$this->request->getBodyParameter('password') !== '' ? ['user_password' => $user] : ''],
            'password' => [$this->request->getBodyParameter('password') !== '' ? ['min' => 8] : ''],
            'confirmPassword' => [['match' => 'password']],
        ]);
        if($validator->validateForm($this->request->getBodyParameters())){
            $user->update([
                'firstName' => $this->request->getBodyParameter('firstName'),
                'lastName' => $this->request->getBodyParameter('lastName'),
                'email' => $this->request->getBodyParameter('email'),
            ]);
            if($this->request->getBodyParameter('password') !== ''){
                $user->update(['password' => $this->request->getBodyParameter('password')]);
            }
            $this->response->redirect('/');
        }else{
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/user/profile/edit');
        }
    }

    public function storeImage(){
        $imageName = uniqid('PFP-', true) . '.' . strtolower(pathinfo($this->request->getFile('image')['name'])['extension']);
        $imagePath = 'storage/profilePictures/' . $imageName;
        move_uploaded_file($this->request->getFile('image')['tmp_name'], $imagePath);
        if(file_exists($this->auth->user()->profilePicture ?? '')){
            unlink($this->auth->user()->profilePicture);
        }
        $this->auth->user()->update(['profilePicture' => $imagePath]);
        $this->response->redirect('/user/profile/edit');
    }

    public function delete() : void{
        $this->auth->user()->delete();
        $this->auth->logout();
        $this->response->redirect('/');
    }

    public function profile() : void{
        $html = $this->renderer->render('profile.html',$this->requiredData);
        $this->response->setContent($html);
    }

    public function editProfile() : void{
        $data = array_merge($this->requiredData, ['user' => $this->auth->user()]);
        $html = $this->renderer->render('editProfile.html',$data);
        $this->response->setContent($html);
    }

    public function myJobs() : void{
        $jobs = $this->auth->user()->jobs();
        $jobCount = count($jobs);
        $html = $this->renderer->render('myJobs.html', array_merge(['jobs' => $jobs, 'jobCount' => $jobCount], $this->requiredData));
        $this->response->setContent($html);
    }   
}