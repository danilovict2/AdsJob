<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Message;

class MessageController extends Controller{

    public function store(array $params){
        $chatRoom = ChatRoom::findOne(['id' => $params['chat_room_id']]);
        if(!$chatRoom){
            $chatRoom = new ChatRoom;
            $chatRoom->create([
                'id' => $params['chat_room_id'],
                'name' => 'npc'
            ]);
            $chatRoom->save();
        }
        $message = new Message;
        $message->create([
            'user_id' => $this->auth->user()->id,
            'chat_room_id' => $chatRoom->id,
            'message' => $this->request->getBodyParameter('message'),
        ]);
        $message->save();
        $this->response->redirect('/chat/' . $params['chat_room_id']);
    }

}