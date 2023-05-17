<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Middleware\AuthMiddleware;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Job;
use AdsJob\Models\Message;
use AdsJob\Models\User;

class ChatRoomController extends Controller{

    public function middleware(){
        $this->registerMiddleware(new AuthMiddleware($this->auth, ['index', 'show', 'messages']));
    }
    
    public function index(){
        $chatRooms = $this->auth->user()->chatRooms();
        $unreadMessages = [];
        
        foreach($chatRooms as $chatRoom){
            $hasUnreadMessage = $this->hasUnreadMessage($chatRoom, $this->auth->user()->id);
            $unreadMessages[$chatRoom->id] = $hasUnreadMessage;
        }
        
        usort($chatRooms, function ($a, $b) use ($unreadMessages){
            $aHasUnread = $unreadMessages[$a->id];
            $bHasUnread = $unreadMessages[$b->id];
            
            if($aHasUnread && !$bHasUnread){
                return -1;
            }elseif(!$aHasUnread && $bHasUnread){
                return 1;
            }
            
            $aDateCreated = strtotime($a->created_at);
            $bDateCreated = strtotime($b->created_at);
            
            if($aDateCreated == $bDateCreated){
                return 0;
            }
            
            return ($aDateCreated < $bDateCreated) ? -1 : 1;
        });
        
        $html = $this->renderer->render('messages.html', array_merge($this->requiredData, compact('chatRooms', 'unreadMessages')));
        $this->response->setContent($html);
    }

    public function show(array $params){
        $jobId = $params['job_id'];
        $chatId = $params['chat_id'];
        $chatRoom = ChatRoom::findOne(['id' => $chatId]);
        
        if(!in_array($chatRoom, $this->auth->user()->chatRooms()) && $chatId !== 'index'){
            $this->response->redirect('/chats');
            return;
        }
        
        $job = Job::findOne(['id' => $jobId]);
        $user1 = $chatRoom ? User::findOne(['id' => $chatRoom->user_1_id]) : [];
        $user2 = $chatRoom ? User::findOne(['id' => $chatRoom->user_2_id]) : [];
        
        $html = $this->renderer->render('chat.html', array_merge($this->requiredData, compact('chatRoom', 'job', 'chatId', 'user1', 'user2')));
        $this->response->setContent($html);
    }

    public function messages(array $params){
        $isAxiosRequest = isset($_SERVER['HTTP_X_AXIOS_REQUEST']) && $_SERVER['HTTP_X_AXIOS_REQUEST'] === 'true';
        
        if(!$isAxiosRequest){
            $this->response->redirect('/');
            return;
        }
        
        $chatId = $params['chat_id'];
        $chatRoom = ChatRoom::findOne(['id' => $chatId]);
        $messages = $chatRoom ? $chatRoom->messages() : [];
        
        $this->response->setContent(json_encode($messages));
    }
    
    private function hasUnreadMessage($chatRoom, $userId){
        $user1Id = $chatRoom->user_1_id;
        $user2Id = $chatRoom->user_2_id;
        
        if ($user1Id !== $userId){
            return (bool) Message::findOne(['chat_room_id' => $chatRoom->id, 'seen' => 0, 'user_id' => $user1Id]);
        }else{
            return (bool) Message::findOne(['chat_room_id' => $chatRoom->id, 'seen' => 0, 'user_id' => $user2Id]);
        }
    }
}
