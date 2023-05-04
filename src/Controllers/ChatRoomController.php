<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\ChatRoom;

class ChatRoomController extends Controller{
    
    public function index(){
        $chatRooms = ChatRoom::all();
        $html = $this->renderer->render('messages.html', array_merge($this->requiredData, compact('chatRooms')));
        $this->response->setContent($html);
    }

    public function show(array $params){
        $chatRoomId = $params['chat_room_id'];
        $chatRoom = ChatRoom::findOne(['id' => $chatRoomId]);
        $messages = [];
        if($chatRoom){
            $messages = $chatRoom->messages();
        }
        $html = $this->renderer->render('chat.html', array_merge($this->requiredData, compact('chatRoom', 'messages', 'chatRoomId')));
        $this->response->setContent($html);
    }
    
}