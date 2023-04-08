<?php declare(strict_types = 1);

namespace AdsJob\Models;

class Review extends Model{

    protected static string $tableName = "job_review";

    protected function attributes() : array{
        return ['user_id', 'job_id', 'review_text', 'review_value'];
    }

    public static function primaryKey() : string{
        return 'id';
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'id');
    }

    public function job(){
        return $this->hasOne(Job::class, 'job_id', 'id');
    }
}