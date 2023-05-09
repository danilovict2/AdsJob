<?php declare(strict_types = 1);

namespace AdsJob\Models;

class Message extends Model{

    protected static string $tableName = "message";

    protected function attributes() : array{
        return ['chat_room_id', 'user_id', 'message', 'seen'];
    }

    public static function primaryKey() : string{
        return 'id';
    }

    public function user() {
        return $this->hasOne(User::class, 'user_id', 'id');
    }

    public function chatRoom() {
        return $this->hasOne(ChatRoom::class, 'chat_room_id', 'id');
    }
}