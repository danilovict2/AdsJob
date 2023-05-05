<?php declare(strict_types = 1);

namespace AdsJob\Models;

class User extends Model{

    protected static string $tableName = "user";

    protected function attributes() : array{
        return ['firstName', 'lastName', 'password', 'email'];
    }

    public static function primaryKey() : string{
        return 'id';
    }

    public function save() : void{
        $this->values['password'] = password_hash($this->values['password'], PASSWORD_DEFAULT);
        parent::save();
    }

    public function update(array $values) : void{
        if(key_exists('password', $values)){
            $values['password'] = password_hash($values['password'], PASSWORD_DEFAULT);
        }
        parent::update($values);
    }

    public function jobs(){
        return $this->hasMany(Job::class, 'id', 'user_id');
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'id', 'user_id');
    }

    public function chatRooms(){
        return array_merge($this->hasMany(ChatRoom::class, 'id', 'user_1_id'), $this->hasMany(ChatRoom::class, 'id', 'user_2_id'));
    }
}