<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\User;
use AdsJob\Middleware\AuthMiddleware;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UserController extends Controller{

    public function middleware(){
        $this->registerMiddleware(new AuthMiddleware($this->auth,['profile', 'editProfile', 'myJobs']));
    }
    
    public function store() : void{
        if(!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            die;
        }
        $validator = new \AdsJob\Validators\Validator([
            'firstName' => ['required', ['max' => 12]],
            'lastName' => ['required' , ['max' => 12]],
            'email' => ['email', 'required', ['unique' => 'User']],
            'password' => ['required', ['min' => 8]],
            'confirmPassword' => ['required', ['match' => 'password']],
        ]);
        if(!$validator->validateForm($this->request->getBodyParameters())){
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/register');
            return;
        }
        $user = new User;
        $user->create($this->request->getBodyParameters());
        $mail = new PHPMailer(true);
        try{
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();

            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = 'adsjobrs@gmail.com';
            $mail->Password = 'jdmttdhfofdwsnmi';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('adsjobrs@gmail.com', 'adsjob.rs');
            $mail->addAddress($this->request->getBodyParameter('email'), $this->request->getBodyParameter('firstName'));

            $mail->isHTML(true);
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        
            $mail->Subject = 'Email Verification';
            $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

            $mail->send();
            $user->verification_code = $verification_code;
        }catch(Exception $e){
            echo '
                <html>
                <head>
                    <title>Error</title>
                    <link rel="stylesheet" href="/light/css/error.css">
                </head>
                <body>
                    <div>
                        <h1>An error has occurred</h1>
                    </div>    
                </body>
                </html>
            ';
            return;
        }
        $user->save();
        $this->response->redirect('/login');
    }

    public function update() : void{
        if(!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            die;
        }
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
        if(!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            die;
        }
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