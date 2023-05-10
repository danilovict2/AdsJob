<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Middleware\AuthMiddleware;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Job;
use AdsJob\Models\Message;
use AdsJob\Models\User;

class ChatRoomController extends Controller{

    public function middleware(){
        $this->registerMiddleware(new AuthMiddleware($this->auth,['index', 'show', 'messages']));
    }
    
    public function index(){
        $chatRooms = array_reverse($this->auth->user()->chatRooms());
        $unreadMessages = [];
        foreach($chatRooms as $chatRoom){
            if($chatRoom->user_1_id !== $this->auth->user()->id){
                $hasUnreadMessage = (bool)Message::findOne(['chat_room_id' => $chatRoom->id, 'seen' => 0, 'user_id' => $chatRoom->user_1_id]);
            }else{
                $hasUnreadMessage = (bool)Message::findOne(['chat_room_id' => $chatRoom->id, 'seen' => 0, 'user_id' => $chatRoom->user_2_id]);
            }
            $unreadMessages[$chatRoom->id] = $hasUnreadMessage;
        }
        $html = $this->renderer->render('messages.html', array_merge($this->requiredData, compact('chatRooms', 'unreadMessages')));
        $this->response->setContent($html);
    }

    public function show(array $params){
        $job = Job::findOne(['id' => $params['job_id']]);
        $chatRoom = ChatRoom::findOne(['id' => $params['chat_id']]);
        if(!in_array($chatRoom, $this->auth->user()->chatRooms()) && $params['chat_id'] !== 'index'){
            $this->response->redirect('/chats');
            return;
        }
        $user1 = [];
        $user2 = [];
        if($chatRoom){
            $user1 = User::findOne(['id' => $chatRoom->user_1_id]);
            $user2 = User::findOne(['id' => $chatRoom->user_2_id]);
        }
        $chatId = $params['chat_id'];
        $html = $this->renderer->render('chat.html', array_merge($this->requiredData, compact('chatRoom', 'job', 'chatId', 'user1', 'user2')));
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