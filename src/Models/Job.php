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
        return $this->hasOne(User::class);
    }

}