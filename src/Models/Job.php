<?php declare(strict_types = 1);

namespace AdsJob\Models;

class Job extends Model{

    protected static string $tableName = "job";

    protected function attributes() : array{
        return ['user_id', 'name', 'location', 'description'];
    }

    public static function primaryKey() : string{
        return 'id';
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'id');
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'id', 'job_id');
    }

    public function images(){
        return $this->hasMany(JobImage::class, 'id', 'job_id');
    }

    public function chatRooms(){
        return $this->hasMany(ChatRoom::class, 'id', 'job_id');
    }
}