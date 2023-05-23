<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\User;
use AdsJob\Middleware\AuthMiddleware;
use AdsJob\Validators\Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller{

    public function middleware(){
        $this->registerMiddleware(new AuthMiddleware($this->auth,['profile', 'editProfile', 'myJobs']));
    }
    
    public function store() : void{
        if(!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            echo "CSRF TOKEN INVALID";
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
        $this->sendVerificationEmail($user);
        $user->save();
        setcookie('unverified_user_id', "$user->id", time() + 604800, secure:true, path:'/');
        $this->response->redirect('/verify/' . $user->id);
    }

    private function sendVerificationEmail(User &$user){
        $mail = new PHPMailer(true);
        try{
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();

            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;

            $mail->Username = $_ENV['MAIL'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom($_ENV['MAIL'], 'adsjob.rs');
            $mail->addAddress($this->request->getBodyParameter('email'), $this->request->getBodyParameter('firstName'));

            $mail->isHTML(true);
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        
            $mail->Subject = 'E-mail verifikacija';
            $mail->Body = '<p>Vaš kod za verifikaciju je: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

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
            die;
        }
    }
    
    public function update() : void{
        if(!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            echo "CSRF TOKEN INVALID";
            die;
        }
        $user = $this->auth->user();
        $validator = new \AdsJob\Validators\Validator([
            'firstName' => ['required', ['max' => 12]],
            'lastName' => ['required', ['max' => 12]],
            'oldPassword' => [$this->request->getBodyParameter('password') !== '' ? ['user_password' => $user] : ''],
            'password' => [$this->request->getBodyParameter('password') !== '' ? ['min' => 8] : ''],
            'confirmPassword' => [['match' => 'password']],
        ]);
        if($validator->validateForm($this->request->getBodyParameters())){
            $user->update([
                'firstName' => $this->request->getBodyParameter('firstName'),
                'lastName' => $this->request->getBodyParameter('lastName'),
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
        $img = Image::make($imagePath);
        $img->resize(260, 260);
        $img->save();
        if(file_exists($this->auth->user()->profilePicture ?? '')){
            unlink($this->auth->user()->profilePicture);
        }
        $this->auth->user()->update(['profilePicture' => $imagePath]);
        $this->response->redirect('/user/profile/edit');
    }

    public function delete() : void{
        if(!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            echo "CSRF TOKEN INVALID";
            die;
        }
        $this->auth->user()->delete();
        $this->auth->logout();
        $this->response->redirect('/');
    }

    public function profile() : void{
        $user = $this->auth->user();
        $html = $this->renderer->render('profile.html',array_merge($this->requiredData, compact('user')));
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
    
    public function verify(array $params){
        if(!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            echo "CSRF TOKEN INVALID";
            die;
        }
        $validator = new Validator([]);
        $user = User::findOne(['id' => $params['user_id']]);
        $verification_code = '';
        foreach($this->request->getBodyParameters() as $key => $value){
            if($key === 'csrf_token')continue;
            $verification_code .= $value;
        }
        if($user->verification_code !== $verification_code){
            $validator->addError('invalid', 'Kod koji ste uneli nije tačan');
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/verify/' . $params['user_id']);
            return;
        }
        
        $user->update([
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);
        $unverified_user_id = $this->request->getCookie('unverified_user_id');
        if(isset($unverified_user_id)){
            setcookie('unverified_user_id', "$user->id", time() - 3600, secure:true, path:'/');
            unset($_COOKIE['unverified_user_id']);
        }
        $this->auth->login($user);
        $this->response->redirect('/');
    }

    public function enableEmailVerifications(){
        $this->auth->user()->email_notifications_enabled = (int)!$this->auth->user()->email_notifications_enabled;
        $this->auth->user()->save();
        $this->response->redirect('/user/profile');
    }
}