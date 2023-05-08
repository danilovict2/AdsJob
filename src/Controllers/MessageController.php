<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Job;
use AdsJob\Models\Message;

class MessageController extends Controller{

    public function store(array $params){
        $chatRoom = new ChatRoom;
        if($params['chat_id'] === 'index'){
            $chatRoom->create([
                'user_1_id' => $this->auth->user()->id,
                'user_2_id' => Job::findOne(['id' => $params['job_id']])->user()->id,
                'job_id' => $params['job_id']
            ]);
            $chatRoom->save();
            $redirect_location = '/chat/' . $chatRoom->id . '/' . $params['job_id'];
            echo json_encode(compact('redirect_location'));
        }else{
            $chatRoom = ChatRoom::findOne(['id' => $params['chat_id'], 'job_id' => $params['job_id']]);
        }
        $message = new Message;
        $message->create([
            'user_id' => $this->auth->user()->id,
            'chat_room_id' => $chatRoom->id,
            'message' => $this->request->getBodyParameter('message'),
        ]);
        $message->save();
        
    }

}