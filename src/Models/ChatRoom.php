<?php declare(strict_types = 1);

namespace AdsJob\Models;

class ChatRoom extends Model{

    protected static string $tableName = "chat_room";

    protected function attributes() : array{
        return ['name'];
    }

    public static function primaryKey() : string{
        return 'id';
    }

    public function messages() {
        return $this->hasMany(Message::class, 'id', 'chat_room_id');
    }
}