<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Job;

class ChatRoomController extends Controller{
    
    public function index(){
        $chatRooms = $this->auth->user()->chatRooms();
        $html = $this->renderer->render('messages.html', array_merge($this->requiredData, compact('chatRooms')));
        $this->response->setContent($html);
    }

    public function show(array $params){
        $job = Job::findOne(['id' => $params['job_id']]);
        $chatRoom = ChatRoom::findOne(['id' => $params['chat_id']]);
        if(!in_array($chatRoom, $this->auth->user()->chatRooms())){
            $this->response->redirect('/chats');
            return;
        }
        $chatId = $params['chat_id'];
        $html = $this->renderer->render('chat.html', array_merge($this->requiredData, compact('chatRoom', 'job', 'chatId')));
        $this->response->setContent($html);
    }

    public function messages(array $params){
        $chatId = $params['chat_id'];
        $chatRoom = ChatRoom::findOne(['id' => $chatId]);
        $messages = [];
        if($chatRoom){
            $messages = $chatRoom->messages();
        }
        $this->response->setContent(json_encode($messages));
    }
    
}