<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Job;
use AdsJob\Models\Message;

class MessageController extends Controller{

    public function store(array $params){
        $chatRoom = ChatRoom::findOne(['id' => $params['chat_id']]);
        if(!$chatRoom){ // If chatRoom does not exist create a new one
            $chatRoom = new ChatRoom;
            $chatRoom->create([
                'user_1_id' => $this->auth->user()->id,
                'user_2_id' => Job::findOne(['id' => $params['job_id']])->user()->id,
                'job_id' => $params['job_id']
            ]);
            $chatRoom->save();
            $redirect_location = '/chat/' . $chatRoom->id . '/' . $params['job_id'];
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

    public function markAsSeen(array $params){
        $message = Message::findOne(['chat_room_id' => $params['chat_id'], 'id' => $params['message_id']]);
        if($message){
            $message->update(['seen' => 1]);
        }
    }

}