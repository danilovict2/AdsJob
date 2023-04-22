<?php declare(strict_types = 1);

namespace AdsJob\Models;

class Message extends Model{

    protected static string $tableName = "message";

    protected function attributes() : array{
        return ['from_user_id', 'to_user_id', 'message'];
    }

    public static function primaryKey() : string{
        return 'id';
    }

    public function sender(){
        return $this->hasOne(User::class, 'from_user_id', 'id');
    }

    public function receiver(){
        return $this->hasOne(User::class, 'to_user_id', 'id');
    }
}