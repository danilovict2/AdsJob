<?php declare(strict_types = 1);

namespace AdsJob\Models;

class JobImage extends Model{

    protected static string $tableName = "job_image";

    protected function attributes() : array{
        return ['job_id', 'imagePath'];
    }

    public static function primaryKey() : string{
        return 'id';
    }

    public function job(){
        return $this->hasOne(Job::class, 'job_id', 'id');
    }
}