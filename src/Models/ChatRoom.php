<?php declare(strict_types = 1);

namespace AdsJob\Models;

class ChatRoom extends Model{

    protected static string $tableName = "chat_room";

    protected function attributes() : array{
        return ['user_1_id', 'user_2_id', 'job_id'];
    }

    public static function primaryKey() : string{
        return 'id';
    }

    public function messages() {
        return $this->hasMany(Message::class, 'id', 'chat_room_id');
    }

    public function job(){
        return $this->hasOne(Job::class, 'job_id', 'id');
    }

    public function user1(){
        return $this->hasOne(User::class, 'user_1_id', 'id');
    }

    public function user2(){
        return $this->hasOne(User::class, 'user_2_id', 'id');
    }
}