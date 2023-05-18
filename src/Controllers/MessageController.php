<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Job;
use AdsJob\Models\Message;
use Spatie\Async\Pool;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MessageController extends Controller{

    public function store(array $params){
        $chatRoom = ChatRoom::findOne(['id' => $params['chat_id']]);
        $job = Job::findOne(['id' => $params['job_id']]);
        if(!$chatRoom){ // If chatRoom does not exist create a new one
            $chatRoom = new ChatRoom;
            $chatRoom->create([
                'user_1_id' => $this->auth->user()->id,
                'user_2_id' => $job->id,
                'job_id' => $params['job_id']
            ]);
            $chatRoom->save();
            $redirect_location = '/chat/' . $chatRoom->id . '/' . $params['job_id'];
            if($job->user()->email_notifications_enabled){
                $this->emailUser($job->user());
            }
            echo json_encode(compact('redirect_location'));
        }
        $message = new Message;
        $message->create([
            'user_id' => $this->auth->user()->id,
            'chat_room_id' => $chatRoom->id,
            'message' => $this->request->getBodyParameter('message'),
        ]);
        $message->save();
        
    }

    private function emailUser($user){
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
            $mail->addAddress($user->email, "$user->firstName $user->lastName");

            $mail->isHTML(true);
        
            $mail->Subject = 'Nova Poruka';
            $mail->Body = '<p>Imate nove poruke na vašim oglasima</p>';

            $mail->send();
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

    public function markAsSeen(array $params){
        $message = Message::findOne(['chat_room_id' => $params['chat_id'], 'id' => $params['message_id']]);
        if($message){
            $message->update(['seen' => 1]);
        }
    }

}